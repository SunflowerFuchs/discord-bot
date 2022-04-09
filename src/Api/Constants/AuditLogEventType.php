<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class AuditLogEventType
{
    const GUILD_UPDATE = 1;
    const CHANNEL_CREATE = 10;
    const CHANNEL_UPDATE = 11;
    const CHANNEL_DELETE = 12;
    const CHANNEL_OVERWRITE_CREATE = 13;
    const CHANNEL_OVERWRITE_UPDATE = 14;
    const CHANNEL_OVERWRITE_DELETE = 15;
    const MEMBER_KICK = 20;
    const MEMBER_PRUNE = 21;
    const MEMBER_BAN_ADD = 22;
    const MEMBER_BAN_REMOVE = 23;
    const MEMBER_UPDATE = 24;
    const MEMBER_ROLE_UPDATE = 25;
    const MEMBER_MOVE = 26;
    const MEMBER_DISCONNECT = 27;
    const BOT_ADD = 28;
    const ROLE_CREATE = 30;
    const ROLE_UPDATE = 31;
    const ROLE_DELETE = 32;
    const INVITE_CREATE = 40;
    const INVITE_UPDATE = 41;
    const INVITE_DELETE = 42;
    const WEBHOOK_CREATE = 50;
    const WEBHOOK_UPDATE = 51;
    const WEBHOOK_DELETE = 52;
    const EMOJI_CREATE = 60;
    const EMOJI_UPDATE = 61;
    const EMOJI_DELETE = 62;
    const MESSAGE_DELETE = 72;
    const MESSAGE_BULK_DELETE = 73;
    const MESSAGE_PIN = 74;
    const MESSAGE_UNPIN = 75;
    const INTEGRATION_CREATE = 80;
    const INTEGRATION_UPDATE = 81;
    const INTEGRATION_DELETE = 82;
    const STAGE_INSTANCE_CREATE = 83;
    const STAGE_INSTANCE_UPDATE = 84;
    const STAGE_INSTANCE_DELETE = 85;
    const STICKER_CREATE = 90;
    const STICKER_UPDATE = 91;
    const STICKER_DELETE = 92;
    const GUILD_SCHEDULED_EVENT_CREATE = 100;
    const GUILD_SCHEDULED_EVENT_UPDATE = 101;
    const GUILD_SCHEDULED_EVENT_DELETE = 102;
    const THREAD_CREATE = 110;
    const THREAD_UPDATE = 111;
    const THREAD_DELETE = 112;
}