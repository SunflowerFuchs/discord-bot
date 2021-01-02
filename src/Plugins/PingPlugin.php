<?php


namespace SunflowerFuchs\DiscordBot\Plugins;


class PingPlugin extends BasePlugin
{
    protected array $commands = [
        'ping' => 'ping'
    ];

    public function ping(string $message, string $channelId, array $messageObject)
    {
        $userId = !empty($messageObject['webhook_id']) ? null : $messageObject['author']['id'];
        if ($userId && $message == 'long') {
            $response = "Hey <@${userId}>. I'm up and running, and having fun!";
            $this->sendMessage($response, $channelId);
        } else {
            $this->sendMessage('Pong', $channelId);
        }
    }
}