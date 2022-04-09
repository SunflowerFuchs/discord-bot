<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class GuildContentFilter
{
    /**
     * media content will not be scanned
     */
    const DISABLED = 0;
    /**
     * media content sent by members without roles will be scanned
     */
    const MEMBERS_WITHOUT_ROLES = 1;
    /**
     * media content sent by all members will be scanned
     */
    const ALL_MEMBERS = 2;
}