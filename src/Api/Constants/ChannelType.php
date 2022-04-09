<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class ChannelType
{
    /**
     * a text channel within a server
     */
    const GUILD_TEXT = 0;
    /**
     * a direct message between users
     */
    const DM = 1;
    /**
     * a voice channel within a server
     */
    const GUILD_VOICE = 2;
    /**
     * a direct message between multiple users
     */
    const GROUP_DM = 3;
    /**
     * an organizational category that contains up to 50 channels
     */
    const GUILD_CATEGORY = 4;
    /**
     * a channel that users can follow and crosspost into their own server
     */
    const GUILD_NEWS = 5;
    /**
     * a temporary sub-channel within a GUILD_NEWS channel
     */
    const GUILD_NEWS_THREAD = 10;
    /**
     * a temporary sub-channel within a GUILD_TEXT channel
     */
    const GUILD_PUBLIC_THREAD = 11;
    /**
     * a temporary sub-channel within a GUILD_TEXT channel that is only viewable by those invited and those with the MANAGE_THREADS permission
     */
    const GUILD_PRIVATE_THREAD = 12;
    /**
     * a voice channel for hosting events with an audience
     */
    const GUILD_STAGE_VOICE = 13;
    /**
     * the channel in a hub containing the listed servers
     */
    const GUILD_DIRECTORY = 14;
    /**
     * a channel that can only contain threads
     */
    const GUILD_FORUM = 15;
}