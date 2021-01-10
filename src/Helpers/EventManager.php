<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

class EventManager
{
    const HELLO = 'HELLO';
    const READY = 'READY';
    const RESUMED = 'RESUMED';
    const RECONNECT = 'RECONNECT';
    const INVALID_SESSION = 'INVALID_SESSION';
    const CHANNEL_CREATE = 'CHANNEL_CREATE';
    const CHANNEL_UPDATE = 'CHANNEL_UPDATE';
    const CHANNEL_DELETE = 'CHANNEL_DELETE';
    const CHANNEL_PINS_UPDATE = 'CHANNEL_PINS_UPDATE';
    const GUILD_CREATE = 'GUILD_CREATE';
    const GUILD_UPDATE = 'GUILD_UPDATE';
    const GUILD_DELETE = 'GUILD_DELETE';
    const GUILD_BAN_ADD = 'GUILD_BAN_ADD';
    const GUILD_BAN_REMOVE = 'GUILD_BAN_REMOVE';
    const GUILD_EMOJIS_UPDATE = 'GUILD_EMOJIS_UPDATE';
    const GUILD_INTEGRATIONS_UPDATE = 'GUILD_INTEGRATIONS_UPDATE';
    const GUILD_MEMBER_ADD = 'GUILD_MEMBER_ADD';
    const GUILD_MEMBER_REMOVE = 'GUILD_MEMBER_REMOVE';
    const GUILD_MEMBER_UPDATE = 'GUILD_MEMBER_UPDATE';
    const GUILD_MEMBERS_CHUNK = 'GUILD_MEMBERS_CHUNK';
    const GUILD_ROLE_CREATE = 'GUILD_ROLE_CREATE';
    const GUILD_ROLE_UPDATE = 'GUILD_ROLE_UPDATE';
    const GUILD_ROLE_DELETE = 'GUILD_ROLE_DELETE';
    const INVITE_CREATE = 'INVITE_CREATE';
    const INVITE_DELETE = 'INVITE_DELETE';
    const MESSAGE_CREATE = 'MESSAGE_CREATE';
    const MESSAGE_UPDATE = 'MESSAGE_UPDATE';
    const MESSAGE_DELETE = 'MESSAGE_DELETE';
    const MESSAGE_DELETE_BULK = 'MESSAGE_DELETE_BULK';
    const MESSAGE_REACTION_ADD = 'MESSAGE_REACTION_ADD';
    const MESSAGE_REACTION_REMOVE = 'MESSAGE_REACTION_REMOVE';
    const MESSAGE_REACTION_REMOVE_ALL = 'MESSAGE_REACTION_REMOVE_ALL';
    const MESSAGE_REACTION_REMOVE_EMOJI = 'MESSAGE_REACTION_REMOVE_EMOJI';
    const PRESENCE_UPDATE = 'PRESENCE_UPDATE';
    const TYPING_START = 'TYPING_START';
    const USER_UPDATE = 'USER_UPDATE';
    const VOICE_STATE_UPDATE = 'VOICE_STATE_UPDATE';
    const VOICE_SERVER_UPDATE = 'VOICE_SERVER_UPDATE';
    const WEBHOOKS_UPDATE = 'WEBHOOKS_UPDATE';
    const INTERACTION_CREATE = 'INTERACTION_CREATE';

    // TODO: support DM events
//    const DM_MESSAGE_CREATE = 'DM_MESSAGE_CREATE';
//    const DM_MESSAGE_UPDATE = 'DM_MESSAGE_UPDATE';
//    const DM_MESSAGE_DELETE = 'DM_MESSAGE_DELETE';
//    const DM_CHANNEL_PINS_UPDATE = 'DM_CHANNEL_PINS_UPDATE';
//    const DM_MESSAGE_REACTION_ADD = 'DM_MESSAGE_REACTION_ADD';
//    const DM_MESSAGE_REACTION_REMOVE = 'DM_MESSAGE_REACTION_REMOVE';
//    const DM_MESSAGE_REACTION_REMOVE_ALL = 'DM_MESSAGE_REACTION_REMOVE_ALL';
//    const DM_MESSAGE_REACTION_REMOVE_EMOJI = 'DM_MESSAGE_REACTION_REMOVE_EMOJI';
//    const DM_TYPING_START = 'DM_TYPING_START';

    /**
     * @var callable[] $subscribers
     */
    protected array $subscribers = [];

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        static $instance;
        return $instance ?: ($instance = new static());
    }

    /**
     * Subscribe to an event
     *
     * @param string $event The event to subscribe to, e.g. {@see EventManager::MESSAGE_CREATE}
     * @param callable $handler
     */
    public function subscribe(string $event, callable $handler)
    {
        if (!isset($this->subscribers[$event])) {
            $this->subscribers[$event] = [];
        }

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
    }

    public function calculateIntent(): int
    {
        $intent = 0;
        $events = array_keys($this->subscribers);
        foreach ($events as $event) {
            switch ($event) {
                case static::GUILD_CREATE:
                case static::GUILD_UPDATE:
                case static::GUILD_DELETE:
                case static::GUILD_ROLE_CREATE:
                case static::GUILD_ROLE_UPDATE:
                case static::GUILD_ROLE_DELETE:
                case static::CHANNEL_CREATE:
                case static::CHANNEL_UPDATE:
                case static::CHANNEL_DELETE:
                case static::CHANNEL_PINS_UPDATE:
                    $intent |= 1 << 0;
                    break;
                case static::GUILD_MEMBER_ADD:
                case static::GUILD_MEMBER_UPDATE:
                case static::GUILD_MEMBER_REMOVE:
                    // Privileged intent
                    $intent |= 1 << 1;
                    break;
                case static::GUILD_BAN_ADD:
                case static::GUILD_BAN_REMOVE:
                    $intent |= 1 << 2;
                    break;
                case static::GUILD_EMOJIS_UPDATE:
                    $intent |= 1 << 3;
                    break;
                case static::GUILD_INTEGRATIONS_UPDATE:
                    $intent |= 1 << 4;
                    break;
                case static::WEBHOOKS_UPDATE:
                    $intent |= 1 << 5;
                    break;
                case static::INVITE_CREATE:
                case static::INVITE_DELETE:
                    $intent |= 1 << 6;
                    break;
                case static::VOICE_STATE_UPDATE:
                    $intent |= 1 << 7;
                    break;
                case static::PRESENCE_UPDATE:
                    // Privileged intent
                    $intent |= 1 << 8;
                    break;
                case static::MESSAGE_CREATE:
                case static::MESSAGE_UPDATE:
                case static::MESSAGE_DELETE:
                case static::MESSAGE_DELETE_BULK:
                    $intent |= 1 << 9;
                    break;
                case static::MESSAGE_REACTION_ADD:
                case static::MESSAGE_REACTION_REMOVE:
                case static::MESSAGE_REACTION_REMOVE_ALL:
                case static::MESSAGE_REACTION_REMOVE_EMOJI:
                    $intent |= 1 << 10;
                    break;
                case static::TYPING_START:
                    $intent |= 1 << 11 | 1 << 14;
                    break;
//                case static::DM_MESSAGE_CREATE:
//                case static::DM_MESSAGE_UPDATE:
//                case static::DM_MESSAGE_DELETE:
//                case static::DM_CHANNEL_PINS_UPDATE:
//                    $intent |= 1 << 12;
//                    break;
//                case static::DM_MESSAGE_REACTION_ADD:
//                case static::DM_MESSAGE_REACTION_REMOVE:
//                case static::DM_MESSAGE_REACTION_REMOVE_ALL:
//                case static::DM_MESSAGE_REACTION_REMOVE_EMOJI:
//                    $intent |= 1 << 13;
//                    break;
//
//                case static::DM_TYPING_START:
//                    $intent |= 1 << 14;
//                    break;
            }
        }

        return $intent;
    }
}