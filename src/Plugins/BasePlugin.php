<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use SunflowerFuchs\DiscordBot\Bot;

abstract class BasePlugin
{
    abstract function init();

    protected function sendMessage(string $message, string $channelId): bool
    {
        return Bot::getInstance()->sendMessage($message, $channelId);
    }
}