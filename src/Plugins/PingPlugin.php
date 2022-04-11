<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Plugins;


use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use SunflowerFuchs\DiscordBot\Api\Objects\User;

class PingPlugin extends BasePlugin
{
    public function init()
    {
        $this->getBot()->registerCommand('ping', fn($msg) => $this->ping($msg));
    }

    protected function ping(Message $msg): bool
    {
        $channelId = $msg->getChannelId();
        if (!$msg->isUserMessage()) {
            return true;
        }

        $params = $msg->getCommandParams($this->getBot()->getPrefix());
        if ($params[0] === 'long') {
            return $this->sendLongPing($channelId, $msg->getAuthor());
        } else {
            return $this->sendPing($channelId);
        }
    }

    protected function sendPing(Snowflake $channelId): bool
    {
        return $this->sendMessage('Pong', $channelId);
    }

    protected function sendLongPing(Snowflake $channelId, User $author): bool
    {
        $userId = $author->getId();
        $response = "Hey <@${userId}>. I'm up, running, and having fun!";
        $response .= PHP_EOL . "We're currently vibing in <#${channelId}>.";
        return $this->sendMessage($response, $channelId);
    }
}