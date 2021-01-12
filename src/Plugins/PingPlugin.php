<?php


namespace SunflowerFuchs\DiscordBot\Plugins;


use SunflowerFuchs\DiscordBot\ApiObjects\Message;

class PingPlugin extends BasePlugin
{
    protected array $commands = [
        'ping' => 'ping'
    ];

    public function ping(Message $msg)
    {
        $channelId = $msg->getChannelId();
        if ($msg->isUserMessage() && $msg->getContent() == 'long') {
            $author = $msg->getAuthor();
            $userId = $author->getId();
            $response = "Hey <@${userId}>. I'm up, running, and having fun!";
            $response .= PHP_EOL . "We're currently vibing in <#${channelId}>.";
            $this->sendMessage($response, $channelId);
        } else {
            $this->sendMessage('Pong', $channelId);
        }
    }
}