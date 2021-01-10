<?php


namespace SunflowerFuchs\DiscordBot\Plugins;


use SunflowerFuchs\DiscordBot\ApiObjects\User;

class PingPlugin extends BasePlugin
{
    protected array $commands = [
        'ping' => 'ping'
    ];

    public function ping(string $message, string $channelId, array $messageObject)
    {
        $author = !empty($messageObject['webhook_id']) ? null : new User($messageObject['author']);
        if ($author && $message == 'long') {
            $userId = $author->getId();
            $response = "Hey <@${userId}>. I'm up, running, and having fun!";
            $response .= PHP_EOL . "We're currently vibing in <#${channelId}>.";
            $this->sendMessage($response, $channelId);
        } else {
            $this->sendMessage('Pong', $channelId);
        }
    }
}