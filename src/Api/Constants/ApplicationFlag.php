<?php
declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class ApplicationFlag
{
    /**
     * Intent required for bots in 100 or more servers to receive presence_update events
     */
    const GATEWAY_PRESENCE = 1 << 12;
    /**
     * Intent required for bots in under 100 servers to receive presence_update events, found in Bot Settings
     */
    const GATEWAY_PRESENCE_LIMITED = 1 << 13;
    /**
     * Intent required for bots in 100 or more servers to receive member-related events like guild_member_add. See list of member-related events under GUILD_MEMBERS
     */
    const GATEWAY_GUILD_MEMBERS = 1 << 14;
    /**
     * Intent required for bots in under 100 servers to receive member-related events like guild_member_add, found in Bot Settings.
     * See list of member-related events under GUILD_MEMBERS
     */
    const GATEWAY_GUILD_MEMBERS_LIMITED = 1 << 15;
    /**
     * Indicates unusual growth of an app that prevents verification
     */
    const VERIFICATION_PENDING_GUILD_LIMIT = 1 << 16;
    /**
     * Indicates if an app is embedded within the Discord client (currently unavailable publicly)
     */
    const EMBEDDED = 1 << 17;
    /**
     * Intent required for bots in 100 or more servers to receive message content
     */
    const GATEWAY_MESSAGE_CONTENT = 1 << 18;
    /**
     * Intent required for bots in under 100 servers to receive message content, found in Bot Settings
     */
    const GATEWAY_MESSAGE_CONTENT_LIMITED = 1 << 19;
}