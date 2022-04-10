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
     * @see https://support.discord.com/hc/en-us/articles/115001580171-Channel-Categories-101
     */
    const GUILD_CATEGORY = 4;
    /**
     * a channel that users can follow and crosspost into their own server
     * @see https://support.discord.com/hc/en-us/articles/360032008192
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
     * @see https://support.discord.com/hc/en-us/articles/1500005513722
     */
    const GUILD_STAGE_VOICE = 13;
    /**
     * the channel in a hub containing the listed servers
     * @see https://support.discord.com/hc/en-us/articles/4406046651927-Discord-Student-Hubs-FAQ
     */
    const GUILD_DIRECTORY = 14;
    /**
     * a channel that can only contain threads
     */
    const GUILD_FORUM = 15;
}