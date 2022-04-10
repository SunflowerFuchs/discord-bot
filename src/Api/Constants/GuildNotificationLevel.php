<?php
declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class GuildNotificationLevel
{

    /**
     * members will receive notifications for all messages by default
     */
    const ALL_MESSAGES = 0;
    /**
     * members will receive notifications only for messages that mention them by default
     */
    const ONLY_MENTIONS = 1;
}