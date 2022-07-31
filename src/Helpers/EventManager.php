<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Helpers;

use Exception;
use SunflowerFuchs\DiscordBot\Api\Constants\ChannelType;
use SunflowerFuchs\DiscordBot\Api\Constants\Events;
use SunflowerFuchs\DiscordBot\Api\Objects\Channel;
use SunflowerFuchs\DiscordBot\Api\Objects\Guild;
use SunflowerFuchs\DiscordBot\Api\Objects\GuildMember;
use SunflowerFuchs\DiscordBot\Api\Objects\Integration;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\MessageInteraction;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use SunflowerFuchs\DiscordBot\Api\Objects\StageInstance;
use SunflowerFuchs\DiscordBot\Api\Objects\ThreadMember;
use SunflowerFuchs\DiscordBot\Api\Objects\User;
use SunflowerFuchs\DiscordBot\Api\Objects\VoiceState;
use SunflowerFuchs\DiscordBot\Bot;

class EventManager
{
    protected const EVENT_ALIASES = [
        Events::MESSAGE_CREATE => Events::DM_MESSAGE_CREATE,
        Events::MESSAGE_UPDATE => Events::DM_MESSAGE_UPDATE,
        Events::MESSAGE_DELETE => Events::DM_MESSAGE_DELETE,
        Events::CHANNEL_PINS_UPDATE => Events::DM_CHANNEL_PINS_UPDATE,
        Events::MESSAGE_REACTION_ADD => Events::DM_MESSAGE_REACTION_ADD,
        Events::MESSAGE_REACTION_REMOVE => Events::DM_MESSAGE_REACTION_REMOVE,
        Events::MESSAGE_REACTION_REMOVE_ALL => Events::DM_MESSAGE_REACTION_REMOVE_ALL,
        Events::MESSAGE_REACTION_REMOVE_EMOJI => Events::DM_MESSAGE_REACTION_REMOVE_EMOJI,
        Events::TYPING_START => Events::DM_TYPING_START,
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
        Events::HELLO => 0,
        Events::READY => 0,
        Events::RESUMED => 0,
        Events::RECONNECT => 0,
        Events::INVALID_SESSION => 0,
        Events::APPLICATION_COMMAND_PERMISSIONS_UPDATE => 0,
        Events::AUTO_MODERATION_RULE_CREATE => self::INTENT_AUTO_MODERATION_CONFIGURATION,
        Events::AUTO_MODERATION_RULE_UPDATE => self::INTENT_AUTO_MODERATION_CONFIGURATION,
        Events::AUTO_MODERATION_RULE_DELETE => self::INTENT_AUTO_MODERATION_CONFIGURATION,
        Events::AUTO_MODERATION_ACTION_EXECUTION => self::INTENT_AUTO_MODERATION_EXECUTION,
        Events::CHANNEL_CREATE => self::INTENT_GUILDS,
        Events::CHANNEL_UPDATE => self::INTENT_GUILDS,
        Events::CHANNEL_DELETE => self::INTENT_GUILDS,
        Events::CHANNEL_PINS_UPDATE => self::INTENT_GUILDS,
        Events::THREAD_CREATE => self::INTENT_GUILDS,
        Events::THREAD_UPDATE => self::INTENT_GUILDS,
        Events::THREAD_DELETE => self::INTENT_GUILDS,
        Events::THREAD_LIST_SYNC => self::INTENT_GUILDS,
        Events::THREAD_MEMBER_UPDATE => self::INTENT_GUILDS,
        Events::THREAD_MEMBERS_UPDATE => self::INTENT_GUILDS | self::INTENT_GUILD_MEMBERS,
        Events::GUILD_CREATE => self::INTENT_GUILDS,
        Events::GUILD_UPDATE => self::INTENT_GUILDS,
        Events::GUILD_DELETE => self::INTENT_GUILDS,
        Events::GUILD_BAN_ADD => self::INTENT_GUILD_BANS,
        Events::GUILD_BAN_REMOVE => self::INTENT_GUILD_BANS,
        Events::GUILD_EMOJIS_UPDATE => self::INTENT_GUILD_EMOJIS_AND_STICKERS,
        Events::GUILD_STICKERS_UPDATE => self::INTENT_GUILD_EMOJIS_AND_STICKERS,
        Events::GUILD_INTEGRATIONS_UPDATE => self::INTENT_GUILD_INTEGRATIONS,
        Events::GUILD_MEMBER_ADD => self::INTENT_GUILD_MEMBERS,
        Events::GUILD_MEMBER_REMOVE => self::INTENT_GUILD_MEMBERS,
        Events::GUILD_MEMBER_UPDATE => self::INTENT_GUILD_MEMBERS,
        Events::GUILD_MEMBERS_CHUNK => 0,
        Events::GUILD_ROLE_CREATE => self::INTENT_GUILDS,
        Events::GUILD_ROLE_UPDATE => self::INTENT_GUILDS,
        Events::GUILD_ROLE_DELETE => self::INTENT_GUILDS,
        Events::GUILD_SCHEDULED_EVENT_CREATE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        Events::GUILD_SCHEDULED_EVENT_UPDATE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        Events::GUILD_SCHEDULED_EVENT_DELETE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        Events::GUILD_SCHEDULED_EVENT_USER_ADD => self::INTENT_GUILD_SCHEDULED_EVENTS,
        Events::GUILD_SCHEDULED_EVENT_USER_REMOVE => self::INTENT_GUILD_SCHEDULED_EVENTS,
        Events::INTEGRATION_CREATE => self::INTENT_GUILD_INTEGRATIONS,
        Events::INTEGRATION_UPDATE => self::INTENT_GUILD_INTEGRATIONS,
        Events::INTEGRATION_DELETE => self::INTENT_GUILD_INTEGRATIONS,
        Events::INTERACTION_CREATE => 0,
        Events::INVITE_CREATE => self::INTENT_GUILD_INVITES,
        Events::INVITE_DELETE => self::INTENT_GUILD_INVITES,
        Events::MESSAGE_CREATE => self::INTENT_GUILD_MESSAGES | self::INTENT_MESSAGE_CONTENT,
        Events::MESSAGE_UPDATE => self::INTENT_GUILD_MESSAGES,
        Events::MESSAGE_DELETE => self::INTENT_GUILD_MESSAGES,
        Events::MESSAGE_DELETE_BULK => self::INTENT_GUILD_MESSAGES,
        Events::MESSAGE_REACTION_ADD => self::INTENT_GUILD_MESSAGE_REACTIONS,
        Events::MESSAGE_REACTION_REMOVE => self::INTENT_GUILD_MESSAGE_REACTIONS,
        Events::MESSAGE_REACTION_REMOVE_ALL => self::INTENT_GUILD_MESSAGE_REACTIONS,
        Events::MESSAGE_REACTION_REMOVE_EMOJI => self::INTENT_GUILD_MESSAGE_REACTIONS,
        Events::PRESENCE_UPDATE => self::INTENT_GUILD_PRESENCES,
        Events::STAGE_INSTANCE_CREATE => self::INTENT_GUILDS,
        Events::STAGE_INSTANCE_DELETE => self::INTENT_GUILDS,
        Events::STAGE_INSTANCE_UPDATE => self::INTENT_GUILDS,
        Events::TYPING_START => self::INTENT_GUILD_MESSAGE_TYPING,
        Events::USER_UPDATE => 0,
        Events::VOICE_STATE_UPDATE => self::INTENT_GUILD_VOICE_STATES,
        Events::VOICE_SERVER_UPDATE => 0,
        Events::WEBHOOKS_UPDATE => self::INTENT_GUILD_WEBHOOKS,
        Events::DM_MESSAGE_CREATE => self::INTENT_DIRECT_MESSAGES,
        Events::DM_MESSAGE_UPDATE => self::INTENT_DIRECT_MESSAGES,
        Events::DM_MESSAGE_DELETE => self::INTENT_DIRECT_MESSAGES,
        Events::DM_CHANNEL_PINS_UPDATE => self::INTENT_DIRECT_MESSAGES,
        Events::DM_MESSAGE_REACTION_ADD => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        Events::DM_MESSAGE_REACTION_REMOVE => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        Events::DM_MESSAGE_REACTION_REMOVE_ALL => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        Events::DM_MESSAGE_REACTION_REMOVE_EMOJI => self::INTENT_DIRECT_MESSAGE_REACTIONS,
        Events::DM_TYPING_START => self::INTENT_DIRECT_MESSAGE_TYPING,
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
     * @return string the id of the event subscription
     * @throws Exception
     */
    public function subscribe(string $event, callable $handler): string
    {
        $eventId = bin2hex(random_bytes(12));
        $this->subscribers[$event] ??= [];
        $this->subscribers[$event][$eventId] = $handler;
        return $eventId;
    }

    public function unsubscribe(string $eventId): bool
    {
        foreach ($this->subscribers as $event => $subscribers) {
            if (isset($this->subscribers[$event][$eventId])) {
                unset($this->subscribers[$event][$eventId]);
                return true;
            }
        }
        return false;
    }

    /**
     * Send out an event
     *
     * @param string $event The event to publish to, e.g. {@see EventManager::MESSAGE_CREATE}
     * @param array $message
     * @param Bot $bot
     */
    public function publish(string $event, array $message, Bot $bot): void
    {
        if (isset(self::EVENT_ALIASES[$event]) && $this->isDmEvent($event, $message, $bot)) {
            $event = self::EVENT_ALIASES[$event];
        }

        // Exit early to save on processing
        if (empty($this->subscribers[$event])) {
            return;
        }

        $parameters = $this->resolveParameters($event, $message);
        foreach ($this->subscribers[$event] as $handler) {
            call_user_func($handler, ...$parameters);
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

    private function isDmEvent(string $event, array $message, Bot $bot): bool
    {
        switch ($event) {
            case Events::MESSAGE_CREATE:
            case Events::MESSAGE_UPDATE:
            case Events::MESSAGE_DELETE:
            case Events::CHANNEL_PINS_UPDATE:
            case Events::MESSAGE_REACTION_ADD:
            case Events::MESSAGE_REACTION_REMOVE:
            case Events::MESSAGE_REACTION_REMOVE_ALL:
            case Events::MESSAGE_REACTION_REMOVE_EMOJI:
            case Events::TYPING_START:
                if (!isset($message['d']['channel_id'])) {
                    return false;
                }

                $channelId = new Snowflake($message['d']['channel_id']);
                $channel = Channel::loadById($bot->getApiClient(), $channelId);
                return $channel->getType() === ChannelType::DM || $channel->getType() === ChannelType::GROUP_DM;
            default:
                return false;
        }
    }

    private function resolveParameters(string $event, array $message): array
    {
        switch ($event) {
            case Events::HELLO:
                return [$message['d']['heartbeat_interval']];
            case Events::READY:
                return [
                    $message['d']['session_id'],
                    new User($message['d']['user']),
                    $message['d']['guilds'], // TODO: implement Unavailable Guild object
                    $message['d']['application'], // TODO: implement Partial Application object
                    $message['d']['shard'] ?? null
                ];
            case Events::RECONNECT:
            case Events::RESUMED:
                return [];
            case Events::CHANNEL_CREATE:
            case Events::CHANNEL_DELETE:
            case Events::THREAD_CREATE:
            case Events::THREAD_DELETE:
                return [new Channel($message['d'])];
            case Events::CHANNEL_UPDATE:
            case Events::THREAD_UPDATE:
                return [empty($message['d']) ? null : new Channel($message['d'])];
            case Events::THREAD_MEMBER_UPDATE:
                $guildId = new Snowflake($message['d']['guild_id']);
                unset($message['d']['guild_id']);
                return [new ThreadMember($message['d']), $guildId];
            case Events::GUILD_CREATE:
                // TODO: clean up additional fields
                return [new Guild($message['d']), $message['d']];
            case Events::GUILD_UPDATE:
                return [new Guild($message['d'])];
            case Events::GUILD_MEMBER_ADD:
                return [new GuildMember($message['d']), new Snowflake($message['d']['guild_id'])];
            case Events::INTEGRATION_CREATE:
            case Events::INTEGRATION_UPDATE:
                return [new Integration($message['d']), new Snowflake($message['d']['guild_id'])];
            case Events::INTERACTION_CREATE:
                return [new MessageInteraction($message['d'])];
            case Events::MESSAGE_CREATE:
            case Events::MESSAGE_UPDATE:
                return [
                    new Message($message['d']),
                    new Snowflake($message['d']['guild_id']),
                    isset($message['d']['member']) ? new GuildMember($message['d']['member']) : null
                ];
            case Events::DM_MESSAGE_CREATE:
            case Events::DM_MESSAGE_UPDATE:
                return [new Message($message['d'])];
            case Events::STAGE_INSTANCE_CREATE:
            case Events::STAGE_INSTANCE_UPDATE:
            case Events::STAGE_INSTANCE_DELETE:
                return [new StageInstance($message['d'])];
            case Events::USER_UPDATE:
                return [new User($message['d'])];
            case Events::VOICE_STATE_UPDATE:
                return [new VoiceState($message['d'])];
            case Events::INVALID_SESSION:
            case Events::APPLICATION_COMMAND_PERMISSIONS_UPDATE:
                // TODO: implement Application Command Permission object
            case Events::AUTO_MODERATION_RULE_CREATE:
            case Events::AUTO_MODERATION_RULE_UPDATE:
            case Events::AUTO_MODERATION_RULE_DELETE:
                // TODO: implement Auto Moderation Rule object
            case Events::AUTO_MODERATION_ACTION_EXECUTION:
                //TODO: implement Auto Moderation Action Execution Event object
            case Events::THREAD_LIST_SYNC:
                // TODO: implement Thread List Sync Event object
            case Events::THREAD_MEMBERS_UPDATE:
                // TODO: implement Thread Member Update Event object
            case Events::GUILD_DELETE:
                // TODO: implement Unavailable Guild object
            case Events::GUILD_BAN_ADD:
                // TODO: implement Guild Ban Add Event object
            case Events::GUILD_BAN_REMOVE:
                // TODO: implement Guild Ban Remove Event object
            case Events::GUILD_EMOJIS_UPDATE:
                // TODO: implement Guild Emojis Update Event object
            case Events::GUILD_STICKERS_UPDATE:
                // TODO: implement Guild Stickers Update Event object
            case Events::GUILD_INTEGRATIONS_UPDATE:
                // TODO: implement Guild Integrations Update Event object
            case Events::GUILD_MEMBER_UPDATE:
                // TODO: implement Guild Member Update Event object
            case Events::GUILD_MEMBER_REMOVE:
                // TODO: implement Guild Member Remove Event object
            case Events::GUILD_MEMBERS_CHUNK:
                // TODO: implement Guild Members Chunk Event object
            case Events::GUILD_ROLE_CREATE:
                // TODO: implement Guild Role Create Event object
            case Events::GUILD_ROLE_UPDATE:
                // TODO: implement Guild Role Update Event object
            case Events::GUILD_ROLE_DELETE:
                // TODO: implement Guild Role Delete Event object
            case Events::GUILD_SCHEDULED_EVENT_CREATE:
            case Events::GUILD_SCHEDULED_EVENT_UPDATE:
            case Events::GUILD_SCHEDULED_EVENT_DELETE:
                // TODO: implement Guild Scheduled Event object
            case Events::GUILD_SCHEDULED_EVENT_USER_ADD:
                // TODO: implement Guild Scheduled Event User Add Event object
            case Events::GUILD_SCHEDULED_EVENT_USER_REMOVE:
                // TODO: implement Guild Scheduled Event User Remove Event object
            case Events::INTEGRATION_DELETE:
                // TODO: implement Integration Delete Event object
            case Events::INVITE_CREATE:
                // TODO: implement Invite Create Event object
            case Events::INVITE_DELETE:
                // TODO: implement Invite Delete Event object
            case Events::MESSAGE_DELETE:
            case Events::DM_MESSAGE_DELETE:
                // TODO: implement Message Delete Event object
            case Events::MESSAGE_DELETE_BULK:
                // TODO: implement Message Delete Bulk Event object
            case Events::MESSAGE_REACTION_ADD:
                // TODO: implement Message Reaction Add Event object
            case Events::MESSAGE_REACTION_REMOVE:
                // TODO: implement Message Reaction Remove Event object
            case Events::MESSAGE_REACTION_REMOVE_ALL:
                // TODO: implement Message Reaction Remove All Event object
            case Events::MESSAGE_REACTION_REMOVE_EMOJI:
                // TODO: implement Message Reaction Remove Emoji Event object
            case Events::PRESENCE_UPDATE:
                // TODO: implement Presence Update Event object
            case Events::TYPING_START:
                // TODO: implement Typing Start Event object
            case Events::VOICE_SERVER_UPDATE:
                // TODO: implement Voice Server Update Event object
            case Events::WEBHOOKS_UPDATE:
                // TODO: implement Webhooks Update Event object
            default:
                return [$message['d']];
        }
    }
}