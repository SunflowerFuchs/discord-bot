<?php


namespace SunflowerFuchs\DiscordBot\Plugins;


use DateTime;
use SunflowerFuchs\DiscordBot\ApiObjects\Message;
use SunflowerFuchs\DiscordBot\Bot;

class UptimePlugin extends BasePlugin
{
    protected DateTime $initTime;

    public function init()
    {
        $this->initTime = new DateTime();
        Bot::getInstance()->registerCommand('uptime', fn($msg) => $this->showUptime($msg));
    }

    public function showUptime(Message $message)
    {
        $startTime = $this->initTime->format('Y-m-d H:i T');
        $uptime = (new DateTime())->diff($this->initTime);
        $strUptime = str_pad($uptime->days ?: 0, 3, '0', STR_PAD_LEFT) . $uptime->format(':%H:%I:%S');

        $this->sendMessage("I've been up and running since ${startTime} (for ${strUptime})", $message->getChannelId());
    }
}
