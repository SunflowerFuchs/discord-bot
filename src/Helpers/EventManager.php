<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Helpers;

class EventManager
{
    /** @var string defines the heartbeat interval */
    public const HELLO = 'HELLO';
    /** @var string contains the initial state information */
    public const READY = 'READY';
    /** @var string response to Resume */
    public const RESUMED = 'RESUMED';
    /** @var string server is going away, client should reconnect to gateway and resume */
    public const RECONNECT = 'RECONNECT';
    /** @var string failure response to Identify or Resume or invalid active session */
    public const INVALID_SESSION = 'INVALID_SESSION';
    /** @var string application command permission was updated */
    public const APPLICATION_COMMAND_PERMISSIONS_UPDATE = 'APPLICATION_COMMAND_PERMISSIONS_UPDATE';
    /** @var string auto moderation rule was created */
    public const AUTO_MODERATION_RULE_CREATE = 'AUTO_MODERATION_RULE_CREATE';
    /** @var string auto moderation rule was updated */
    public const AUTO_MODERATION_RULE_UPDATE = 'AUTO_MODERATION_RULE_UPDATE';
    /** @var string auto moderation rule was deleted */
    public const AUTO_MODERATION_RULE_DELETE = 'AUTO_MODERATION_RULE_DELETE';
    /** @var string auto moderation rule was triggered and an action was executed (e.g. a message was blocked) */
    public const AUTO_MODERATION_ACTION_EXECUTION = 'AUTO_MODERATION_ACTION_EXECUTION';
    /** @var string new guild channel created */
    public const CHANNEL_CREATE = 'CHANNEL_CREATE';
    /** @var string channel was updated */
    public const CHANNEL_UPDATE = 'CHANNEL_UPDATE';
    /** @var string channel was deleted */
    public const CHANNEL_DELETE = 'CHANNEL_DELETE';
    /** @var string message was pinned or unpinned */
    public const CHANNEL_PINS_UPDATE = 'CHANNEL_PINS_UPDATE';
    /** @var string thread created, also sent when being added to a private thread */
    public const THREAD_CREATE = 'THREAD_CREATE';
    /** @var string thread was updated */
    public const THREAD_UPDATE = 'THREAD_UPDATE';
    /** @var string thread was deleted */
    public const THREAD_DELETE = 'THREAD_DELETE';
    /** @var string sent when gaining access to a channel, contains all active threads in that channel */
    public const THREAD_LIST_SYNC = 'THREAD_LIST_SYNC';
    /** @var string thread member for the current user was updated */
    public const THREAD_MEMBER_UPDATE = 'THREAD_MEMBER_UPDATE';
    /** @var string some user(s) were added to or removed from a thread */
    public const THREAD_MEMBERS_UPDATE = 'THREAD_MEMBERS_UPDATE';
    /** @var string lazy-load for unavailable guild, guild became available, or user joined a new guild */
    public const GUILD_CREATE = 'GUILD_CREATE';
    /** @var string guild was updated */
    public const GUILD_UPDATE = 'GUILD_UPDATE';
    /** @var string guild became unavailable, or user left/was removed from a guild */
    public const GUILD_DELETE = 'GUILD_DELETE';
    /** @var string user was banned from a guild */
    public const GUILD_BAN_ADD = 'GUILD_BAN_ADD';
    /** @var string user was unbanned from a guild */
    public const GUILD_BAN_REMOVE = 'GUILD_BAN_REMOVE';
    /** @var string guild emojis were updated */
    public const GUILD_EMOJIS_UPDATE = 'GUILD_EMOJIS_UPDATE';
    /** @var string guild stickers were updated */
    public const GUILD_STICKERS_UPDATE = 'GUILD_STICKERS_UPDATE';
    /** @var string guild integration was updated */
    public const GUILD_INTEGRATIONS_UPDATE = 'GUILD_INTEGRATIONS_UPDATE';
    /** @var string new user joined a guild */
    public const GUILD_MEMBER_ADD = 'GUILD_MEMBER_ADD';
    /** @var string user was removed from a guild */
    public const GUILD_MEMBER_REMOVE = 'GUILD_MEMBER_REMOVE';
    /** @var string guild member was updated */
    public const GUILD_MEMBER_UPDATE = 'GUILD_MEMBER_UPDATE';
    /** @var string response to Request Guild Members */
    public const GUILD_MEMBERS_CHUNK = 'GUILD_MEMBERS_CHUNK';
    /** @var string guild role was created */
    public const GUILD_ROLE_CREATE = 'GUILD_ROLE_CREATE';
    /** @var string guild role was updated */
    public const GUILD_ROLE_UPDATE = 'GUILD_ROLE_UPDATE';
    /** @var string guild role was deleted */
    public const GUILD_ROLE_DELETE = 'GUILD_ROLE_DELETE';
    /** @var string guild scheduled event was created */
    public const GUILD_SCHEDULED_EVENT_CREATE = 'GUILD_SCHEDULED_EVENT_CREATE';
    /** @var string guild scheduled event was updated */
    public const GUILD_SCHEDULED_EVENT_UPDATE = 'GUILD_SCHEDULED_EVENT_UPDATE';
    /** @var string guild scheduled event was deleted */
    public const GUILD_SCHEDULED_EVENT_DELETE = 'GUILD_SCHEDULED_EVENT_DELETE';
    /** @var string user subscribed to a guild scheduled event */
    public const GUILD_SCHEDULED_EVENT_USER_ADD = 'GUILD_SCHEDULED_EVENT_USER_ADD';
    /** @var string user unsubscribed from a guild scheduled event */
    public const GUILD_SCHEDULED_EVENT_USER_REMOVE = 'GUILD_SCHEDULED_EVENT_USER_REMOVE';
    /** @var string guild integration was created */
    public const INTEGRATION_CREATE = 'INTEGRATION_CREATE';
    /** @var string guild integration was updated */
    public const INTEGRATION_UPDATE = 'INTEGRATION_UPDATE';
    /** @var string guild integration was deleted */
    public const INTEGRATION_DELETE = 'INTEGRATION_DELETE';
    /** @var string user used an interaction, such as an Application Command */
    public const INTERACTION_CREATE = 'INTERACTION_CREATE';
    /** @var string invite to a channel was created */
    public const INVITE_CREATE = 'INVITE_CREATE';
    /** @var string invite to a channel was deleted */
    public const INVITE_DELETE = 'INVITE_DELETE';
    /** @var string message was created */
    public const MESSAGE_CREATE = 'MESSAGE_CREATE';
    /** @var string message was edited */
    public const MESSAGE_UPDATE = 'MESSAGE_UPDATE';
    /** @var string message was deleted */
    public const MESSAGE_DELETE = 'MESSAGE_DELETE';
    /** @var string multiple messages were deleted at once */
    public const MESSAGE_DELETE_BULK = 'MESSAGE_DELETE_BULK';
    /** @var string user reacted to a message */
    public const MESSAGE_REACTION_ADD = 'MESSAGE_REACTION_ADD';
    /** @var string user removed a reaction from a message */
    public const MESSAGE_REACTION_REMOVE = 'MESSAGE_REACTION_REMOVE';
    /** @var string all reactions were explicitly removed from a message */
    public const MESSAGE_REACTION_REMOVE_ALL = 'MESSAGE_REACTION_REMOVE_ALL';
    /** @var string all reactions for a given emoji were explicitly removed from a message */
    public const MESSAGE_REACTION_REMOVE_EMOJI = 'MESSAGE_REACTION_REMOVE_EMOJI';
    /** @var string user was updated */
    public const PRESENCE_UPDATE = 'PRESENCE_UPDATE';
    /** @var string stage instance was created */
    public const STAGE_INSTANCE_CREATE = 'STAGE_INSTANCE_CREATE';
    /** @var string stage instance was deleted or closed */
    public const STAGE_INSTANCE_DELETE = 'STAGE_INSTANCE_DELETE';
    /** @var string stage instance was updated */
    public const STAGE_INSTANCE_UPDATE = 'STAGE_INSTANCE_UPDATE';
    /** @var string user started typing in a channel */
    public const TYPING_START = 'TYPING_START';
    /** @var string properties about the user changed */
    public const USER_UPDATE = 'USER_UPDATE';
    /** @var string someone joined, left, or moved a voice channel */
    public const VOICE_STATE_UPDATE = 'VOICE_STATE_UPDATE';
    /** @var string guild'S voice server was updated */
    public const VOICE_SERVER_UPDATE = 'VOICE_SERVER_UPDATE';
    /** @var string guild channel webhook was created, update, or deleted */
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

    protected const EVENT_ALIASES = [
        self::MESSAGE_CREATE => self::DM_MESSAGE_CREATE,
        self::MESSAGE_UPDATE => self::DM_MESSAGE_UPDATE,
        self::MESSAGE_DELETE => self::DM_MESSAGE_DELETE,
        self::CHANNEL_PINS_UPDATE => self::DM_CHANNEL_PINS_UPDATE,
        self::MESSAGE_REACTION_ADD => self::DM_MESSAGE_REACTION_ADD,
        self::MESSAGE_REACTION_REMOVE => self::DM_MESSAGE_REACTION_REMOVE,
        self::MESSAGE_REACTION_REMOVE_ALL => self::DM_MESSAGE_REACTION_REMOVE_ALL,
        self::MESSAGE_REACTION_REMOVE_EMOJI => self::DM_MESSAGE_REACTION_REMOVE_EMOJI,
        self::TYPING_START => self::DM_TYPING_START,
    ];

    protected const INTENT_GUILDS = 1 << 0;
    protected const INTENT_GUILD_MEMBERS = 1 << 1;
    protected const INTENT_GUILD_BANS = 1 << 2;
    protected const INTENT_GUILD_EMOJIS_AND_STICKERS = 1 << 3;
    protected const INTENT_GUILD_INTEGRATIONS = 1 << 4;
    protected const INTENT_GUILD_WEBHOOKS = 1 << 5;
    protected const INTENT_GUILD_INVITES = 1 << 6;
    protected const INTENT_GUILD_VOICE_STATES = 1 << 7;
    protected const INTENT_GUILD_PRESENCES = 1 << 8;
    protected const INTENT_GUILD_MESSAGES = 1 << 9;
    protected const INTENT_GUILD_MESSAGE_REACTIONS = 1 << 10;
    protected const INTENT_GUILD_MESSAGE_TYPING = 1 << 11;
    protected const INTENT_DIRECT_MESSAGES = 1 << 12;
    protected const INTENT_DIRECT_MESSAGE_REACTIONS = 1 << 13;
    protected const INTENT_DIRECT_MESSAGE_TYPING = 1 << 14;
    protected const INTENT_MESSAGE_CONTENT = 1 << 15;
    protected const INTENT_GUILD_SCHEDULED_EVENTS = 1 << 16;
    protected const INTENT_AUTO_MODERATION_CONFIGURATION = 1 << 20;
    protected const INTENT_AUTO_MODERATION_EXECUTION = 1 << 21;

    protected const INTENT_MAP = [
        self::HELLO => 0,
        self::READY => 0,
        self::RESUMED => 0,
        self::RECONNECT => 0,
        self::INVALID_SESSION => 0,
        self::APPLICATION_COMMAND_PERMISSIONS_UPDATE => 0,
        self::AUTO_MODERATION_RULE_CREATE => self::INTENT_AUTO_MODERATION_CONFIGURATION,
        self::AUTO_MODERATION_RULE_UPDATE => self::INTENT_AUTO_MODERATION_CONFIGURATION,
        self::AUTO_MODERATION_RULE_DELETE => self::INTENT_AUTO_MODERATION_CONFIGURATION,
        self::AUTO_MODERATION_ACTION_EXECUTION => self::INTENT_AUTO_MODERATION_EXECUTION,
        self::CHANNEL_CREATE => self::INTENT_GUILDS,
        self::CHANNEL_UPDATE => self::INTENT_GUILDS,
        self::CHANNEL_DELETE => self::INTENT_GUILDS,
        self::CHANNEL_PINS_UPDATE => self::INTENT_GUILDS,
        self::THREAD_CREATE => self::INTENT_GUILDS,
        self::THREAD_UPDATE => self::INTENT_GUILDS,
        self::THREAD_DELETE => self::INTENT_GUILDS,
        self::THREAD_LIST_SYNC => self::INTENT_GUILDS,
        self::THREAD_MEMBER_UPDATE => self::INTENT_GUILDS,
        self::THREAD_MEMBERS_UPDATE => self::INTENT_GUILDS | self::INTENT_GUILD_MEMBERS,
        self::GUILD_CREATE => self::INTENT_GUILDS,
        self::GUILD_UPDATE => self::INTENT_GUILDS,
        self::GUILD_DELETE => self::INTENT_GUILDS,
        self::GUILD_BAN_ADD => self::INTENT_GUILD_BANS,
        self::GUILD_BAN_REMOVE => self::INTENT_GUILD_BANS,
        self::GUILD_EMOJIS_UPDATE => self::INTENT_GUILD_EMOJIS_AND_STICKERS,
        self::GUILD_STICKERS_UPDATE => self::INTENT_GUILD_EMOJIS_AND_STICKERS,
        self::GUILD_INTEGRATIONS_UPDATE => self::INTENT_GUILD_INTEGRATIONS,
        self::GUILD_MEMBER_ADD => self::INTENT_GUILD_MEMBERS,
        self::GUILD_MEMBER_REMOVE => self::INTENT_GUILD_MEMBERS,
        self::GUILD_MEMBER_UPDATE => self::INTENT_GUILD_MEMBERS,
        self::GUILD_MEMBERS_CHUNK => 0,
        self::GUILD_ROLE_CREATE => self::INTENT_GUILDS,
        self::GUILD_ROLE_UPDATE => self::INTENT_GUILDS,
        self::GUILD_ROLE_DELETE => self::INTENT_GUILDS,
        self::GUILD_SCHEDULED_EVENT_CREATE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        self::GUILD_SCHEDULED_EVENT_UPDATE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        self::GUILD_SCHEDULED_EVENT_DELETE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        self::GUILD_SCHEDULED_EVENT_USER_ADD => self::INTENT_GUILD_SCHEDULED_EVENTS,
        self::GUILD_SCHEDULED_EVENT_USER_REMOVE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        self::INTEGRATION_CREATE => self::INTENT_GUILD_INTEGRATIONS,
        self::INTEGRATION_UPDATE => self::INTENT_GUILD_INTEGRATIONS,
        self::INTEGRATION_DELETE => self::INTENT_GUILD_INTEGRATIONS,
        self::INTERACTION_CREATE => 0,
        self::INVITE_CREATE => self::INTENT_GUILD_INVITES,
        self::INVITE_DELETE => self::INTENT_GUILD_INVITES,
        self::MESSAGE_CREATE => self::INTENT_GUILD_MESSAGES | self::INTENT_MESSAGE_CONTENT,
        self::MESSAGE_UPDATE => self::INTENT_GUILD_MESSAGES,
        self::MESSAGE_DELETE => self::INTENT_GUILD_MESSAGES,
        self::MESSAGE_DELETE_BULK => self::INTENT_GUILD_MESSAGES,
        self::MESSAGE_REACTION_ADD => self::INTENT_GUILD_MESSAGE_REACTIONS,
        self::MESSAGE_REACTION_REMOVE => self::INTENT_GUILD_MESSAGE_REACTIONS,
        self::MESSAGE_REACTION_REMOVE_ALL => self::INTENT_GUILD_MESSAGE_REACTIONS,
        self::MESSAGE_REACTION_REMOVE_EMOJI => self::INTENT_GUILD_MESSAGE_REACTIONS,
        self::PRESENCE_UPDATE => self::INTENT_GUILD_PRESENCES,
        self::STAGE_INSTANCE_CREATE => self::INTENT_GUILDS,
        self::STAGE_INSTANCE_DELETE => self::INTENT_GUILDS,
        self::STAGE_INSTANCE_UPDATE => self::INTENT_GUILDS,
        self::TYPING_START => self::INTENT_GUILD_MESSAGE_TYPING,
        self::USER_UPDATE => 0,
        self::VOICE_STATE_UPDATE => self::INTENT_GUILD_VOICE_STATES,
        self::VOICE_SERVER_UPDATE => 0,
        self::WEBHOOKS_UPDATE => self::INTENT_GUILD_WEBHOOKS,
        self::DM_MESSAGE_CREATE => self::INTENT_DIRECT_MESSAGES,
        self::DM_MESSAGE_UPDATE => self::INTENT_DIRECT_MESSAGES,
        self::DM_MESSAGE_DELETE => self::INTENT_DIRECT_MESSAGES,
        self::DM_CHANNEL_PINS_UPDATE => self::INTENT_DIRECT_MESSAGES,
        self::DM_MESSAGE_REACTION_ADD => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        self::DM_MESSAGE_REACTION_REMOVE => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        self::DM_MESSAGE_REACTION_REMOVE_ALL => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        self::DM_MESSAGE_REACTION_REMOVE_EMOJI => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        self::DM_TYPING_START => self::INTENT_DIRECT_MESSAGE_TYPING,
    ];

    /**
     * @var callable[] $subscribers
     */
    protected array $subscribers = [];

    /**
     * Subscribe to an event
     *
     * @param string $event The event to subscribe to, e.g. {@see EventManager::MESSAGE_CREATE}
     * @param callable $handler
     */
    public function subscribe(string $event, callable $handler)
    {
        $this->subscribers[$event] ??= [];
        $this->subscribers[$event][] = $handler;
    }

    /**
     * Send out an event
     *
     * @param string $event The event to publish to, e.g. {@see EventManager::MESSAGE_CREATE}
     * @param array $message
     */
    public function publish(string $event, array $message)
    {
        foreach ($this->subscribers[$event] ?? [] as $handler) {
            call_user_func($handler, $message);
        }

        if (isset(self::EVENT_ALIASES[$event])) {
            // TODO: forward DM events
            // $this->publish(self::EVENT_ALIASES[$event], $message);
        }
    }

    public function calculateIntent(): int
    {
        return array_reduce(
            array_keys($this->subscribers),
            fn(int $carry, string $event) => $carry | self::INTENT_MAP[$event],
            0
        );
    }
}