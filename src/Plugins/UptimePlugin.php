<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Plugins;


use DateTime;
use Exception;
use SunflowerFuchs\DiscordBot\Api\Constants\Events;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;

class UptimePlugin extends BasePlugin
{
    protected DateTime $initTime;

    /**
     * @throws Exception
     */
    public function init()
    {
        $this->initTime = new DateTime();
        $this->getBot()->registerCommand('uptime', [$this, 'showUptime']);

        // Enable asking for uptime via DM
        // I should probably create a $bot->registerDmCommand function for this
        $this->getBot()->subscribeToEvent(Events::DM_MESSAGE_CREATE, function (Message $message) {
            $prefix = $this->getBot()->getPrefix();
            if ($message->getCommand($prefix) === 'uptime') {
                $this->showUptime($message);
            }
        });
    }

    public function showUptime(Message $message): bool
    {
        $startTime = $this->initTime->format('Y-m-d H:i T');
        $uptime = (new DateTime())->diff($this->initTime);
        $strUptime = str_pad((string)($uptime->days ?: 0), 3, '0', STR_PAD_LEFT) . $uptime->format(':%H:%I:%S');

        $this->sendMessage("I've been up and running since ${startTime} (for ${strUptime})", $message->getChannelId());
        return true;
    }
}
