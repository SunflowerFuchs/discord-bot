<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


class Permissions
{
    /**
     * Allows creation of instant invites
     */
    const CREATE_INSTANT_INVITE = 1 << 0;
    /**
     * Allows kicking members
     */
    const KICK_MEMBERS = 1 << 1;
    /**
     * Allows banning members
     */
    const BAN_MEMBERS = 1 << 2;
    /**
     * Allows all permissions and bypasses channel permission overwrites
     */
    const ADMINISTRATOR = 1 << 3;
    /**
     * Allows management and editing of channels
     */
    const MANAGE_CHANNELS = 1 << 4;
    /**
     * Allows management and editing of the guild
     */
    const MANAGE_GUILD = 1 << 5;
    /**
     * Allows for the addition of reactions to messages
     */
    const ADD_REACTIONS = 1 << 6;
    /**
     * Allows for viewing of audit logs
     */
    const VIEW_AUDIT_LOG = 1 << 7;
    /**
     * Allows for using priority speaker in a voice channel
     */
    const PRIORITY_SPEAKER = 1 << 8;
    /**
     * Allows the user to go live
     */
    const STREAM = 1 << 9;
    /**
     * Allows guild members to view a channel, which includes reading messages in text channels
     */
    const VIEW_CHANNEL = 1 << 10;
    /**
     * Allows for sending messages in a channel
     */
    const SEND_MESSAGES = 1 << 11;
    /**
     * Allows for sending of /tts messages
     */
    const SEND_TTS_MESSAGES = 1 << 12;
    /**
     * Allows for deletion of other users messages
     */
    const MANAGE_MESSAGES = 1 << 13;
    /**
     * Links sent by users with this permission will be auto-embedded
     */
    const EMBED_LINKS = 1 << 14;
    /**
     * Allows for uploading images and files
     */
    const ATTACH_FILES = 1 << 15;
    /**
     * Allows for reading of message history
     */
    const READ_MESSAGE_HISTORY = 1 << 16;
    /**
     * Allows for using the @ everyone tag to notify all users in a channel, and the @ here tag to notify all online users in a channel
     */
    const MENTION_EVERYONE = 1 << 17;
    /**
     * Allows the usage of custom emojis from other servers
     */
    const USE_EXTERNAL_EMOJIS = 1 << 18;
    /**
     * Allows for viewing guild insights
     */
    const VIEW_GUILD_INSIGHTS = 1 << 19;
    /**
     * Allows for joining of a voice channel
     */
    const CONNECT = 1 << 20;
    /**
     * Allows for speaking in a voice channel
     */
    const SPEAK = 1 << 21;
    /**
     * Allows for muting members in a voice channel
     */
    const MUTE_MEMBERS = 1 << 22;
    /**
     * Allows for deafening of members in a voice channel
     */
    const DEAFEN_MEMBERS = 1 << 23;
    /**
     * Allows for moving of members between voice channels
     */
    const MOVE_MEMBERS = 1 << 24;
    /**
     * Allows for using voice-activity-detection in a voice channel
     */
    const USE_VAD = 1 << 25;
    /**
     * Allows for modification of own nickname
     */
    const CHANGE_NICKNAME = 1 << 26;
    /**
     * Allows for modification of other users nicknames
     */
    const MANAGE_NICKNAMES = 1 << 27;
    /**
     * Allows management and editing of roles
     */
    const MANAGE_ROLES = 1 << 28;
    /**
     * Allows management and editing of webhooks
     */
    const MANAGE_WEBHOOKS = 1 << 29;
    /**
     * Allows management and editing of emojis
     */
    const MANAGE_EMOJIS = 1 << 30;
}