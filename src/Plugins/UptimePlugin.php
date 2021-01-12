<?php


namespace SunflowerFuchs\DiscordBot\Plugins;


use DateTime;
use SunflowerFuchs\DiscordBot\ApiObjects\Message;
use SunflowerFuchs\DiscordBot\Bot;

class UptimePlugin extends BasePlugin
{
    protected array $commands = [
        'uptime' => 'showUptime'
    ];

    protected DateTime $initTime;

    public function init(Bot $bot): BasePlugin
    {
        $this->initTime = new DateTime();
        return parent::init($bot);
    }

    public function showUptime(Message $message)
    {
        $startTime = $this->initTime->format('Y-m-d H:i T');
        $uptime = (new DateTime())->diff($this->initTime);
        $strUptime = str_pad($uptime->days ?: 0, 3, '0') . $uptime->format(':%H:%I:%S');

        $this->sendMessage("I've been up and running since ${startTime} (for ${strUptime})", $message->getChannelId());
    }
}