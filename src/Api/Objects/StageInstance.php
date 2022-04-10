<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class StageInstance
{
    /**
     * The id of this Stage instance
     */
    protected Snowflake $id;
    /**
     * The guild id of the associated Stage channel
     */
    protected Snowflake $guild_id;
    /**
     * The id of the associated Stage channel
     */
    protected Snowflake $channel_id;
    /**
     * The topic of the Stage instance (1-120 characters)
     */
    protected string $topic;
    /**
     * The privacy level of the Stage instance
     */
    protected int $privacy_level;
    /**
     * The id of the scheduled event for this Stage instance
     */
    protected ?Snowflake $guild_scheduled_event_id;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->guild_id = new Snowflake($data['guild_id']);
        $this->channel_id = new Snowflake($data['channel_id']);
        $this->topic = $data['topic'];
        $this->privacy_level = $data['privacy_level'];
        $this->guild_scheduled_event_id = !empty($data['guild_scheduled_event_id']) ? new Snowflake($data['guild_scheduled_event_id']) : null;
    }

    /**
     * The id of this Stage instance
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * The guild id of the associated Stage channel
     * @return Snowflake
     */
    public function getGuildId(): Snowflake
    {
        return $this->guild_id;
    }

    /**
     * The id of the associated Stage channel
     * @return Snowflake
     */
    public function getChannelId(): Snowflake
    {
        return $this->channel_id;
    }

    /**
     * The topic of the Stage instance (1-120 characters)
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * The privacy level of the Stage instance
     * @return int
     */
    public function getPrivacyLevel(): int
    {
        return $this->privacy_level;
    }

    /**
     * The id of the scheduled event for this Stage instance
     * @return ?Snowflake
     */
    public function getGuildScheduledEventId(): ?Snowflake
    {
        return $this->guild_scheduled_event_id;
    }


}