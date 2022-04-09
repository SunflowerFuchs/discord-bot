<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class GuildVerificationLevel
{
    /**
     * unrestricted
     */
    const NONE = 0;
    /**
     * must have verified email on account
     */
    const LOW = 1;
    /**
     * must be registered on Discord for longer than 5 minutes
     */
    const MEDIUM = 2;
    /**
     * must be a member of the server for longer than 10 minutes
     */
    const HIGH = 3;
    /**
     * must have a verified phone number
     */
    const VERY_HIGH = 4;
}