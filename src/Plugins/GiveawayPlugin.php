<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use Medoo\Medoo;
use SunflowerFuchs\DiscordBot\Api\Objects\AllowedMentions;
use SunflowerFuchs\DiscordBot\Api\Objects\Emoji;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;

class GiveawayPlugin extends BasePlugin
{
    protected Medoo $db;

    public function init()
    {
        // TODO: initialize Database
        // $this->initDatabase();
        $this->getBot()->registerCommand('giveaway', [$this, 'parseCommand']);
    }

    public function parseCommand(Message $message): bool
    {
        $params = $message->getCommandParams($this->getBot()->getPrefix());
        switch ($params[1] ?? '') {
            case 'start':
                return $this->askForTime($message);
            case 'stop':
                // TODO: implement stop
            default:
                $subcommands = ['start', 'stop'];
                $this->sendMessage("Invalid subcommand; Known subcommands:\n " . implode("\n ", $subcommands),
                    $message->getChannelId());
                return true;
        }
    }

    protected function askForTime(Message $message): bool
    {
        $this->sendMessageAndAwaitResponse(
            'What date/time do you want the giveaway to end?',
            $message->getChannelId(),
            $message->getAuthor()->getId(),
            [$this, 'parseTime']
        );

        return true;
    }

    protected function parseTime(Message $message): bool
    {
        $time = strtotime($message->getContent(), $message->getTimestamp());
        if (!$time) {
            $this->sendMessage(
                'I could not understand that time. Try something like "15 July 2022 4:33 PM" or "midnight next thursday"',
                $message->getChannelId()
            );
            return false;
        }

        $this->sendMessageAndAwaitResponse(
            "Is <t:${time}> correct?",
            $message->getChannelId(),
            $message->getAuthor()->getId(),
            fn(Message $message) => $this->confirmTime($message, $time)
        );

        return true;
    }

    protected function confirmTime(Message $message, int $drawing): bool
    {
        // If the answer isn't just yes, restart from the beginning
        if (strtolower($message->getContent()) !== 'yes') {
            return $this->askForTime($message);
        }

        $this->sendMessageAndAwaitResponse(
            'What are you giving away?',
            $message->getChannelId(),
            $message->getAuthor()->getId(),
            fn(Message $message) => $this->parseWinnings($message, $drawing)
        );
        return true;
    }

    protected function parseWinnings(Message $message, int $drawing): bool
    {
        $winnings = $message->getContent();
        $this->sendMessageAndAwaitResponse(
            'What channel should i announce the giveaway and winners in?',
            $message->getChannelId(),
            $message->getAuthor()->getId(),
            fn(Message $message) => $this->parseChannel($message, $drawing, $winnings)
        );
        return true;
    }

    protected function parseChannel(Message $message, int $drawing, string $winnings): bool
    {
        $success = preg_match('/^<#(\d+)>$/', $message->getContent(), $matches);
        if (!$success) {
            $this->sendMessage(
                'Please send the channel i should announce the giveaway/winners in',
                $message->getChannelId()
            );
        }
        $channelId = new Snowflake($matches[1]);

        $this->sendMessageAndAwaitResponse(
            'What emoji should people react with?',
            $message->getChannelId(),
            $message->getAuthor()->getId(),
            fn(Message $message) => $this->parseEmoji($message, $drawing, $winnings, $channelId)
        );
        return true;
    }

    protected function parseEmoji(Message $message, int $drawing, string $winnings, Snowflake $channelId): bool
    {
        $content = $message->getContent();
        if (Emoji::isStandardEmoji($content)) {
            $emojiString = $content;
        } else {
            $isEmoji = preg_match('/^<a?:(\w+):(\d+)>$/', $content, $matches);
            if (!$isEmoji) {
                $this->sendMessage('Please send the emoji people should react with', $message->getChannelId());
                return false;
            }

            $emoji = Emoji::loadById(
                $this->getBot()->getApiClient(),
                $message->getGuildId(),
                new Snowflake($matches[2])
            );
            if ($emoji === null) {
                $this->sendMessage(
                    'Cannot use emoji. Has to bei either a default emoji, or a custom emoji from the current server.',
                    $message->getChannelId()
                );
                return false;
            }

            $emojiId = $emoji->getId();
            $emojiName = $emoji->getName();
            $emojiString = "${emojiName}:${emojiId}";
        }

        // TODO: store in db
        // $this->addGiveawayEntry();
        $success = $this->announceGiveaway($drawing, $winnings, $emojiString, $channelId);
        if (!$success) {
            $this->sendMessage('An error occurred while announcing the giveaway, sorry', $channelId);
            return false;
        }
        return true;
    }

    protected function announceGiveaway(int $drawing, string $winnings, string $emoji, Snowflake $channelId): bool
    {
        $msgEmoji = Emoji::isStandardEmoji($emoji) ? $emoji : "<:${emoji}>";
        $giveawayMessage = <<<EOL
It's giveaway time! @everyone
We are giving away ${winnings}.
React to this message with ${msgEmoji} to participate.
The winner will be drawn on <t:${drawing}>.
Good luck!
EOL;

        $allowedMention = (new AllowedMentions())->allowEveryone();
        $message = $this->sendMessage($giveawayMessage, $channelId, $allowedMention);
        if ($message instanceof Message) {
            if ($this->addReaction($emoji, $message->getId(), $channelId)) {
                return true;
            }
        }

        return false;
    }
}