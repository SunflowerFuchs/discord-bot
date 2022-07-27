<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class Events
{
    /**
     * defines the heartbeat interval
     * @link https://discord.com/developers/docs/topics/gateway#hello
     */
    public const HELLO = 'HELLO';
    /**
     * contains the initial state information
     * @link https://discord.com/developers/docs/topics/gateway#ready
     */
    public const READY = 'READY';
    /**
     * response to Resume
     * @link https://discord.com/developers/docs/topics/gateway#resumed
     */
    public const RESUMED = 'RESUMED';
    /**
     * server is going away, client should reconnect to gateway and resume
     * @link https://discord.com/developers/docs/topics/gateway#reconnect
     */
    public const RECONNECT = 'RECONNECT';
    /**
     * failure response to Identify or Resume or invalid active session
     * @link https://discord.com/developers/docs/topics/gateway#invalid-session
     */
    public const INVALID_SESSION = 'INVALID_SESSION';
    /**
     * application command permission was updated
     * @link https://discord.com/developers/docs/topics/gateway#application-command-permissions-update
     */
    public const APPLICATION_COMMAND_PERMISSIONS_UPDATE = 'APPLICATION_COMMAND_PERMISSIONS_UPDATE';
    /**
     * auto moderation rule was created
     * @link https://discord.com/developers/docs/topics/gateway#auto-moderation-rule-create
     */
    public const AUTO_MODERATION_RULE_CREATE = 'AUTO_MODERATION_RULE_CREATE';
    /**
     * auto moderation rule was updated
     * @link https://discord.com/developers/docs/topics/gateway#auto-moderation-rule-update
     */
    public const AUTO_MODERATION_RULE_UPDATE = 'AUTO_MODERATION_RULE_UPDATE';
    /**
     * auto moderation rule was deleted
     * @link https://discord.com/developers/docs/topics/gateway#auto-moderation-rule-delete
     */
    public const AUTO_MODERATION_RULE_DELETE = 'AUTO_MODERATION_RULE_DELETE';
    /**
     * auto moderation rule was triggered and an action was executed (e.g. a message was blocked)
     * @link https://discord.com/developers/docs/topics/gateway#auto-moderation-action-execution
     */
    public const AUTO_MODERATION_ACTION_EXECUTION = 'AUTO_MODERATION_ACTION_EXECUTION';
    /**
     * new guild channel created
     * @link https://discord.com/developers/docs/topics/gateway#channel-create
     */
    public const CHANNEL_CREATE = 'CHANNEL_CREATE';
    /**
     * channel was updated
     * @link https://discord.com/developers/docs/topics/gateway#channel-update
     */
    public const CHANNEL_UPDATE = 'CHANNEL_UPDATE';
    /**
     * channel was deleted
     * @link https://discord.com/developers/docs/topics/gateway#channel-delete
     */
    public const CHANNEL_DELETE = 'CHANNEL_DELETE';
    /**
     * message was pinned or unpinned
     * @link https://discord.com/developers/docs/topics/gateway#channel-pins-update
     */
    public const CHANNEL_PINS_UPDATE = 'CHANNEL_PINS_UPDATE';
    /**
     * thread created, also sent when being added to a private thread
     * @link https://discord.com/developers/docs/topics/gateway#thread-create
     */
    public const THREAD_CREATE = 'THREAD_CREATE';
    /**
     * thread was updated
     * @link https://discord.com/developers/docs/topics/gateway#thread-update
     */
    public const THREAD_UPDATE = 'THREAD_UPDATE';
    /**
     * thread was deleted
     * @link https://discord.com/developers/docs/topics/gateway#thread-delete
     */
    public const THREAD_DELETE = 'THREAD_DELETE';
    /**
     * sent when gaining access to a channel, contains all active threads in that channel
     * @link https://discord.com/developers/docs/topics/gateway#thread-list-sync
     */
    public const THREAD_LIST_SYNC = 'THREAD_LIST_SYNC';
    /**
     * thread member for the current user was updated
     * @link https://discord.com/developers/docs/topics/gateway#thread-member-update
     */
    public const THREAD_MEMBER_UPDATE = 'THREAD_MEMBER_UPDATE';
    /**
     * some user(s) were added to or removed from a thread
     * @link https://discord.com/developers/docs/topics/gateway#thread-members-update
     */
    public const THREAD_MEMBERS_UPDATE = 'THREAD_MEMBERS_UPDATE';
    /**
     * lazy-load for unavailable guild, guild became available, or user joined a new guild
     * @link https://discord.com/developers/docs/topics/gateway#guild-create
     */
    public const GUILD_CREATE = 'GUILD_CREATE';
    /**
     * guild was updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-update
     */
    public const GUILD_UPDATE = 'GUILD_UPDATE';
    /**
     * guild became unavailable, or user left/was removed from a guild
     * @link https://discord.com/developers/docs/topics/gateway#guild-delete
     */
    public const GUILD_DELETE = 'GUILD_DELETE';
    /**
     * user was banned from a guild
     * @link https://discord.com/developers/docs/topics/gateway#guild-ban-add
     */
    public const GUILD_BAN_ADD = 'GUILD_BAN_ADD';
    /**
     * user was unbanned from a guild
     * @link https://discord.com/developers/docs/topics/gateway#guild-ban-remove
     */
    public const GUILD_BAN_REMOVE = 'GUILD_BAN_REMOVE';
    /**
     * guild emojis were updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-emojis-update
     */
    public const GUILD_EMOJIS_UPDATE = 'GUILD_EMOJIS_UPDATE';
    /**
     * guild stickers were updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-stickers-update
     */
    public const GUILD_STICKERS_UPDATE = 'GUILD_STICKERS_UPDATE';
    /**
     * guild integration was updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-integrations-update
     */
    public const GUILD_INTEGRATIONS_UPDATE = 'GUILD_INTEGRATIONS_UPDATE';
    /**
     * new user joined a guild
     * @link https://discord.com/developers/docs/topics/gateway#guild-member-add
     */
    public const GUILD_MEMBER_ADD = 'GUILD_MEMBER_ADD';
    /**
     * user was removed from a guild
     * @link https://discord.com/developers/docs/topics/gateway#guild-member-remove
     */
    public const GUILD_MEMBER_REMOVE = 'GUILD_MEMBER_REMOVE';
    /**
     * guild member was updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-member-update
     */
    public const GUILD_MEMBER_UPDATE = 'GUILD_MEMBER_UPDATE';
    /**
     * response to Request Guild Members
     * @link https://discord.com/developers/docs/topics/gateway#guild-members-chunk
     */
    public const GUILD_MEMBERS_CHUNK = 'GUILD_MEMBERS_CHUNK';
    /**
     * guild role was created
     * @link https://discord.com/developers/docs/topics/gateway#guild-role-create
     */
    public const GUILD_ROLE_CREATE = 'GUILD_ROLE_CREATE';
    /**
     * guild role was updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-role-update
     */
    public const GUILD_ROLE_UPDATE = 'GUILD_ROLE_UPDATE';
    /**
     * guild role was deleted
     * @link https://discord.com/developers/docs/topics/gateway#guild-role-delete
     */
    public const GUILD_ROLE_DELETE = 'GUILD_ROLE_DELETE';
    /**
     * guild scheduled event was created
     * @link https://discord.com/developers/docs/topics/gateway#guild-scheduled-event-create
     */
    public const GUILD_SCHEDULED_EVENT_CREATE = 'GUILD_SCHEDULED_EVENT_CREATE';
    /**
     * guild scheduled event was updated
     * @link https://discord.com/developers/docs/topics/gateway#guild-scheduled-event-update
     */
    public const GUILD_SCHEDULED_EVENT_UPDATE = 'GUILD_SCHEDULED_EVENT_UPDATE';
    /**
     * guild scheduled event was deleted
     * @link https://discord.com/developers/docs/topics/gateway#guild-scheduled-event-delete
     */
    public const GUILD_SCHEDULED_EVENT_DELETE = 'GUILD_SCHEDULED_EVENT_DELETE';
    /**
     * user subscribed to a guild scheduled event
     * @link https://discord.com/developers/docs/topics/gateway#guild-scheduled-event-user-add
     */
    public const GUILD_SCHEDULED_EVENT_USER_ADD = 'GUILD_SCHEDULED_EVENT_USER_ADD';
    /**
     * user unsubscribed from a guild scheduled event
     * @link https://discord.com/developers/docs/topics/gateway#guild-scheduled-event-user-remove
     */
    public const GUILD_SCHEDULED_EVENT_USER_REMOVE = 'GUILD_SCHEDULED_EVENT_USER_REMOVE';
    /**
     * guild integration was created
     * @link https://discord.com/developers/docs/topics/gateway#integration-create
     */
    public const INTEGRATION_CREATE = 'INTEGRATION_CREATE';
    /**
     * guild integration was updated
     * @link https://discord.com/developers/docs/topics/gateway#integration-update
     */
    public const INTEGRATION_UPDATE = 'INTEGRATION_UPDATE';
    /**
     * guild integration was deleted
     * @link https://discord.com/developers/docs/topics/gateway#integration-delete
     */
    public const INTEGRATION_DELETE = 'INTEGRATION_DELETE';
    /**
     * user used an interaction, such as an Application Command
     * @link https://discord.com/developers/docs/topics/gateway#interaction-create
     */
    public const INTERACTION_CREATE = 'INTERACTION_CREATE';
    /**
     * invite to a channel was created
     * @link https://discord.com/developers/docs/topics/gateway#invite-create
     */
    public const INVITE_CREATE = 'INVITE_CREATE';
    /**
     * invite to a channel was deleted
     * @link https://discord.com/developers/docs/topics/gateway#invite-delete
     */
    public const INVITE_DELETE = 'INVITE_DELETE';
    /**
     * message was created
     * @link https://discord.com/developers/docs/topics/gateway#message-create
     */
    public const MESSAGE_CREATE = 'MESSAGE_CREATE';
    /**
     * message was edited
     * @link https://discord.com/developers/docs/topics/gateway#message-update
     */
    public const MESSAGE_UPDATE = 'MESSAGE_UPDATE';
    /**
     * message was deleted
     * @link https://discord.com/developers/docs/topics/gateway#message-delete
     */
    public const MESSAGE_DELETE = 'MESSAGE_DELETE';
    /**
     * multiple messages were deleted at once
     * @link https://discord.com/developers/docs/topics/gateway#message-delete-bulk
     */
    public const MESSAGE_DELETE_BULK = 'MESSAGE_DELETE_BULK';
    /**
     * user reacted to a message
     * @link https://discord.com/developers/docs/topics/gateway#message-reaction-add
     */
    public const MESSAGE_REACTION_ADD = 'MESSAGE_REACTION_ADD';
    /**
     * user removed a reaction from a message
     * @link https://discord.com/developers/docs/topics/gateway#message-reaction-remove
     */
    public const MESSAGE_REACTION_REMOVE = 'MESSAGE_REACTION_REMOVE';
    /**
     * all reactions were explicitly removed from a message
     * @link https://discord.com/developers/docs/topics/gateway#message-reaction-remove-all
     */
    public const MESSAGE_REACTION_REMOVE_ALL = 'MESSAGE_REACTION_REMOVE_ALL';
    /**
     * all reactions for a given emoji were explicitly removed from a message
     * @link https://discord.com/developers/docs/topics/gateway#message-reaction-remove-emoji
     */
    public const MESSAGE_REACTION_REMOVE_EMOJI = 'MESSAGE_REACTION_REMOVE_EMOJI';
    /**
     * user was updated
     * @link https://discord.com/developers/docs/topics/gateway#presence-update
     */
    public const PRESENCE_UPDATE = 'PRESENCE_UPDATE';
    /**
     * stage instance was created
     * @link https://discord.com/developers/docs/topics/gateway#stage-instance-create
     */
    public const STAGE_INSTANCE_CREATE = 'STAGE_INSTANCE_CREATE';
    /**
     * stage instance was deleted or closed
     * @link https://discord.com/developers/docs/topics/gateway#stage-instance-delete
     */
    public const STAGE_INSTANCE_DELETE = 'STAGE_INSTANCE_DELETE';
    /**
     * stage instance was updated
     * @link https://discord.com/developers/docs/topics/gateway#stage-instance-update
     */
    public const STAGE_INSTANCE_UPDATE = 'STAGE_INSTANCE_UPDATE';
    /**
     * user started typing in a channel
     * @link https://discord.com/developers/docs/topics/gateway#typing-start
     */
    public const TYPING_START = 'TYPING_START';
    /**
     * properties about the user changed
     * @link https://discord.com/developers/docs/topics/gateway#user-update
     */
    public const USER_UPDATE = 'USER_UPDATE';
    /**
     * someone joined, left, or moved a voice channel
     * @link https://discord.com/developers/docs/topics/gateway#voice-state-update
     */
    public const VOICE_STATE_UPDATE = 'VOICE_STATE_UPDATE';
    /**
     * guild's voice server was updated
     * @link https://discord.com/developers/docs/topics/gateway#voice-server-update
     */
    public const VOICE_SERVER_UPDATE = 'VOICE_SERVER_UPDATE';
    /**
     * guild channel webhook was created, update, or deleted
     * @link https://discord.com/developers/docs/topics/gateway#webhooks-update
     */
    public const WEBHOOKS_UPDATE = 'WEBHOOKS_UPDATE';

    /**
     * @var string
     * @see EventManager::MESSAGE_CREATE
     */
    public const DM_MESSAGE_CREATE = 'DM_MESSAGE_CREATE';
    /**
     * @var string
     * @see EventManager::MESSAGE_UPDATE
     */
    public const DM_MESSAGE_UPDATE = 'DM_MESSAGE_UPDATE';
    /**
     * @var string
     * @see EventManager::MESSAGE_DELETE
     */
    public const DM_MESSAGE_DELETE = 'DM_MESSAGE_DELETE';
    /**
     * @var string
     * @see EventManager::CHANNEL_PINS_UPDATE
     */
    public const DM_CHANNEL_PINS_UPDATE = 'DM_CHANNEL_PINS_UPDATE';
    /**
     * @var string
     * @see EventManager::MESSAGE_REACTION_ADD
     */
    public const DM_MESSAGE_REACTION_ADD = 'DM_MESSAGE_REACTION_ADD';
    /**
     * @var string
     * @see EventManager::MESSAGE_REACTION_REMOVE
     */
    public const DM_MESSAGE_REACTION_REMOVE = 'DM_MESSAGE_REACTION_REMOVE';
    /**
     * @var string
     * @see EventManager::MESSAGE_REACTION_REMOVE_ALL
     */
    public const DM_MESSAGE_REACTION_REMOVE_ALL = 'DM_MESSAGE_REACTION_REMOVE_ALL';
    /**
     * @var string
     * @see EventManager::MESSAGE_REACTION_REMOVE_EMOJI
     */
    public const DM_MESSAGE_REACTION_REMOVE_EMOJI = 'DM_MESSAGE_REACTION_REMOVE_EMOJI';
    /**
     * @var string
     * @see EventManager::TYPING_START
     */
    public const DM_TYPING_START = 'DM_TYPING_START';
}