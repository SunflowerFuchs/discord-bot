<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use InvalidArgumentException;
use Spatie\Emoji\Emoji as EmojiLib;
use SunflowerFuchs\DiscordBot\Api\Constants\Events;
use SunflowerFuchs\DiscordBot\Api\Objects\AllowedMentions;
use SunflowerFuchs\DiscordBot\Api\Objects\Emoji;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use TikTok\Driver\NativeDriver;
use TikTok\TikTokDownloader;

class TiktokPlugin extends BasePlugin
{
    protected const REMOVE_EMOJI = EmojiLib::CHARACTER_CROSS_MARK_BUTTON;

    protected TikTokDownloader $downloader;
    protected Client $guzzleClient;

    public function __construct()
    {
        // NativeDriver is much smaller than SnaptikDriver, otherwise I'd prefer to use that
        $this->downloader = new TikTokDownloader(new NativeDriver());
        $this->guzzleClient = new Client();
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        $this->subscribeToEvent(Events::MESSAGE_CREATE, [$this, 'checkPostedMessage']);
        $this->subscribeToEvent(Events::MESSAGE_REACTION_ADD, [$this, 'checkReaction']);
    }

    public function checkPostedMessage(Message $message): bool
    {
        if (!$message->isUserMessage()) {
            // Posted via webhook/bot? don't do anything
            return true;
        }

        $content = trim($message->getContent());
        try {
            $uri = new Uri($content);
        } catch (InvalidArgumentException $e) {
            // Cannot parse content as URL, return
            return true;
        }
        if (!str_ends_with($uri->getHost(), 'tiktok.com')) {
            // url isn't from tiktok, return
            return true;
        }

        $origPosterId = $message->getAuthor()->getId();
        $videoUrl = 'https://' . $uri->getHost() . $uri->getPath();
        try {
            $tempFile = $this->getTempFile();
            $downloadUrl = $this->downloader->getVideo($videoUrl);
            // download the actual video to $tempFile
            $res = $this->guzzleClient->get($downloadUrl, ['sink' => $tempFile]);
            if ($res->getStatusCode() !== 200) {
                $this->getBot()->getLogger()->notice('Error while downloading tiktok', [
                    'url' => $videoUrl,
                    'error' => $res->getBody()->getContents()
                ]);

                $this->sendSelfDestructingMessage('Could not download tiktok, sorry', $message->getChannelId());
                return false;
            }
            $filesize = filesize($tempFile);
            if ($filesize > 8388284) {
                $this->sendSelfDestructingMessage('Tiktok is too big to embed', $message->getChannelId(), 3);
                // As a fallback, send the direct download link to be embedded
                $tiktokMsg = $this->sendFallbackMessage($downloadUrl, $origPosterId, $message->getChannelId());
            } else {
                // send the file as a discord attachment
                $tiktokMsg = Message::create(
                    $this->getBot()->getApiClient(),
                    $message->getChannelId(),
                    "Posted by <@${origPosterId}>",
                    (new AllowedMentions())->allowUser($origPosterId),
                    files: [$tempFile],
                );
            }
            unlink($tempFile);

            if (!$tiktokMsg instanceof Message) {
                $this->getBot()->getLogger()->warning('Could not upload tiktok', [
                    'url' => $videoUrl,
                    'error' => $tiktokMsg
                ]);
                $this->sendSelfDestructingMessage('Could not upload tiktok, sorry', $message->getChannelId(), 3);
                // As a fallback, send the direct download link to be embedded
                $tiktokMsg = $this->sendFallbackMessage($downloadUrl, $origPosterId, $message->getChannelId());
                if (!$tiktokMsg instanceof Message) {
                    // We already sent an error message, so nothing to do here
                    return false;
                }
            }

            $success = $this->addReaction(self::REMOVE_EMOJI, $tiktokMsg->getId(), $tiktokMsg->getChannelId());
            if (!$success) {
                $this->getBot()->getLogger()->warning('Could not append reaction to message', [
                    'msg' => $tiktokMsg->getId(),
                    'emoji' => self::REMOVE_EMOJI
                ]);
                return true;
            }

            // And now we can delete the original message
            Message::delete($this->getBot()->getApiClient(), $message->getId(), $message->getChannelId());

            return true;
        } catch (Exception $e) {
            if (isset($tempFile) && is_file($tempFile)) {
                unlink($tempFile);
            }
            $this->getBot()->getLogger()->notice('Exception while downloading tiktok', [
                'url' => $videoUrl,
                'error' => $e->getMessage()
            ]);

            $this->sendSelfDestructingMessage('Could not download tiktok, sorry',
                $message->getChannelId());
            return true;
        }
    }

    public function checkReaction(array $data): bool
    {
        $messageId = new Snowflake($data['message_id']);
        $channelId = new Snowflake($data['channel_id']);
        $userId = new Snowflake($data['user_id']);
        $emoji = new Emoji($data['emoji']);

        $message = Message::loadById($this->getBot()->getApiClient(), $messageId, $channelId);
        if ($emoji->getName() !== self::REMOVE_EMOJI || !$message->mentionsUser($userId)) {
            // someone who isn't the poster reacted, ignore it
            return true;
        }

        return Message::delete($this->getBot()->getApiClient(), $messageId, $channelId);
    }

    /**
     * @throws Exception
     */
    protected function getTempFile(string $extension = 'mp4'): string
    {
        $dir = $this->getDataDir();
        $identifier = bin2hex(random_bytes(5));
        $extension = $extension ? ".${extension}" : '';
        return "${dir}${identifier}${extension}";
    }

    protected function sendFallbackMessage(string $downloadUrl, Snowflake $origPosterId, Snowflake $channelId): ?Message
    {
        $downloadUrl = new Uri($downloadUrl);
        $shortDownloadUrl = 'https://' . $downloadUrl->getHost() . $downloadUrl->getPath();
        return $this->sendMessage(
            "Posted by <@${origPosterId}>\n${shortDownloadUrl}",
            $channelId,
            (new AllowedMentions())->allowUser($origPosterId)
        );
    }
}