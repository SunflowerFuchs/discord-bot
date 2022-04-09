<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects\Constants;

class GuildMultiFactor
{
    /**
     * guild has no MFA/2FA requirement for moderation actions
     */
    const NONE = 0;
    /**
     * guild has a 2FA requirement for moderation actions
     */
    const ELEVATED = 1;
}