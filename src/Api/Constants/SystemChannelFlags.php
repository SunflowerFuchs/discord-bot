<?php
declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class SystemChannelFlags
{
    /**
     * Suppress member join notifications
     */
    const SUPPRESS_JOIN_NOTIFICATIONS = 1 << 0;
    /**
     * Suppress server boost notifications
     */
    const SUPPRESS_PREMIUM_SUBSCRIPTIONS = 1 << 1;
    /**
     * Suppress server setup tips
     */
    const SUPPRESS_GUILD_REMINDER_NOTIFICATIONS = 1 << 2;
    /**
     * Hide member join sticker reply buttons
     */
    const SUPPRESS_JOIN_NOTIFICATION_REPLIES = 1 << 3;
}