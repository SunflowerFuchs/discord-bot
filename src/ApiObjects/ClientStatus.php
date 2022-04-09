<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

use SunflowerFuchs\DiscordBot\ApiObjects\Constants\Status;

class ClientStatus
{
    /**
     * the user's status set for an active desktop (Windows, Linux, Mac) application session
     */
    protected string $desktop;
    /**
     * the user's status set for an active mobile (iOS, Android) application session
     */
    protected string $mobile;
    /**
     * the user's status set for an active web (browser, bot account) application session
     */
    protected string $web;

    public function __construct(array $data)
    {
        $this->desktop = $data['desktop'] ?? Status::OFFLINE;
        $this->mobile = $data['mobile'] ?? Status::OFFLINE;
        $this->web = $data['web'] ?? Status::OFFLINE;
    }
}