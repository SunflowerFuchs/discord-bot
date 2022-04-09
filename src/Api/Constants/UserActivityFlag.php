<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class UserActivityFlag
{
    const INSTANCE = 1 << 0;
    const JOIN = 1 << 1;
    const SPECTATE = 1 << 2;
    const JOIN_REQUEST = 1 << 3;
    const SYNC = 1 << 4;
    const PLAY = 1 << 5;
    const PARTY_PRIVACY_FRIENDS = 1 << 6;
    const PARTY_PRIVACY_VOICE_CHANNEL = 1 << 7;
    const EMBEDDED = 1 << 8;
}