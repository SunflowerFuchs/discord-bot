<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use InvalidArgumentException;
use SunflowerFuchs\DiscordBot\Api\Constants\Events;
use SunflowerFuchs\DiscordBot\Api\Objects\AllowedMentions;
use SunflowerFuchs\DiscordBot\Api\Objects\Emoji;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Reaction;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use TikTok\Driver\SnaptikDriver;
use TikTok\TikTokDownloader;

class TiktokPlugin extends BasePlugin
{
    protected const REMOVE_EMOJI = '❎';

    protected TikTokDownloader $downloader;
    protected Client $guzzleClient;

    public function __construct()
    {
        $this->downloader = new TikTokDownloader(new SnaptikDriver());
        $this->guzzleClient = new Client();
    }

    public function init()
    {
        $this->getBot()->subscribeToEvent(Events::MESSAGE_CREATE, [$this, 'checkPostedMessage']);
        $this->getBot()->subscribeToEvent(Events::MESSAGE_REACTION_ADD, [$this, 'checkReaction']);
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
                $this->sendMessage('Could not download tiktok, sorry', $message->getChannelId());
                return false;
            }

            // send the file as a discord attachment
            $origPosterId = $message->getAuthor()->getId();
            $tiktokMsg = Message::create(
                $this->getBot()->getApiClient(),
                $message->getChannelId(),
                "Posted by <@${origPosterId}>",
                (new AllowedMentions())->allowUser($origPosterId),
                files: [$tempFile],
            );
            unlink($tempFile);

            if (!$tiktokMsg) {
                $this->getBot()->getLogger()->warning('Could not upload tiktok', ['url' => $videoUrl]);
                $this->sendMessage('Could not upload tiktok, sorry', $message->getChannelId());
                return false;
            }

            $success = Reaction::create(
                $this->getBot()->getApiClient(),
                $message->getChannelId(),
                $tiktokMsg->getId(),
                self::REMOVE_EMOJI
            );
            if (!$success) {
                $this->getBot()->getLogger()->warning('Could not append reaction to message', [
                    'msg' => $tiktokMsg->getId(),
                    'emoji' => self::REMOVE_EMOJI
                ]);
                return true;
            }

            return true;
        } catch (Exception $e) {
            if (isset($tempFile) && is_file($tempFile)) {
                unlink($tempFile);
            }
            $this->getBot()->getLogger()->notice('Error while downloading tiktok', [
                'url' => $videoUrl,
                'error' => $e->getMessage()
            ]);
            $this->sendMessage('Could not download tiktok, sorry', $message->getChannelId());
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
        $dir = $this->getBot()->getDataDir() . 'tiktoks/';
        if (!is_dir($dir) && !mkdir($dir, 0700, false)) {
            throw new Exception('Could not create temp directory');
        }

        $identifier = bin2hex(random_bytes(5));
        $extension = $extension ? ".${extension}" : '';
        return "${dir}${identifier}${extension}";
    }
}