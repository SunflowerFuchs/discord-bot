<?php


namespace SunflowerFuchs\DiscordBot\Plugins;


class PingPlugin extends BasePlugin
{
    protected array $commands = [
        'ping' => 'ping'
    ];

    public function ping(string $message, string $channelId)
    {
        $this->sendMessage('Pong', $channelId);
    }
}