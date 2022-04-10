<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class MessageType
{
    const DEFAULT = 0;
    const RECIPIENT_ADD = 1;
    const RECIPIENT_REMOVE = 2;
    const CALL = 3;
    const CHANNEL_NAME_CHANGE = 4;
    const CHANNEL_ICON_CHANGE = 5;
    const CHANNEL_PINNED_MESSAGE = 6;
    const GUILD_MEMBER_JOIN = 7;
    const USER_PREMIUM_GUILD_SUBSCRIPTION = 8;
    const USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_1 = 9;
    const USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_2 = 10;
    const USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_3 = 11;
    const CHANNEL_FOLLOW_ADD = 12;
    const GUILD_DISCOVERY_DISQUALIFIED = 14;
    const GUILD_DISCOVERY_REQUALIFIED = 15;
    const GUILD_DISCOVERY_GRACE_PERIOD_INITIAL_WARNING = 16;
    const GUILD_DISCOVERY_GRACE_PERIOD_FINAL_WARNING = 17;
    const THREAD_CREATED = 18;
    const REPLY = 19;
    const CHAT_INPUT_COMMAND = 20;
    const THREAD_STARTER_MESSAGE = 21;
    const GUILD_INVITE_REMINDER = 22;
    const CONTEXT_MENU_COMMAND = 23;
}