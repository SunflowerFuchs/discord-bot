<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class GuildScheduledEvent
{
    /**
     * the id of the scheduled event
     */
    protected Snowflake $id;
    /**
     * the guild id which the scheduled event belongs to
     */
    protected Snowflake $guild_id;
    /**
     * the channel id in which the scheduled event will be hosted, or null if scheduled entity type is EXTERNAL
     * @see https://discord.com/developers/docs/resources/guild-scheduled-event#guild-scheduled-event-object-field-requirements-by-entity-type
     */
    protected ?Snowflake $channel_id;
    /**
     * the id of the user that created the scheduled event
     */
    protected ?Snowflake $creator_id;
    /**
     * the name of the scheduled event (1-100 characters)
     */
    protected string $name;
    /**
     * the description of the scheduled event (1-1000 characters)
     */
    protected string $description;
    /**
     * the time the scheduled event will start
     */
    protected int $scheduled_start_time;
    /**
     * the time the scheduled event will end, required if entity_type is EXTERNAL
     * @see https://discord.com/developers/docs/resources/guild-scheduled-event#guild-scheduled-event-object-field-requirements-by-entity-type
     */
    protected int $scheduled_end_time;
    /**
     * the privacy level of the scheduled event
     */
    protected int $privacy_level;
    /**
     * the status of the scheduled event
     */
    protected int $status;
    /**
     * the type of the scheduled event
     */
    protected int $entity_type;
    /**
     * the id of an entity associated with a guild scheduled event
     */
    protected ?Snowflake $entity_id;
    /**
     * additional metadata for the guild scheduled event
     * @see https://discord.com/developers/docs/resources/guild-scheduled-event#guild-scheduled-event-object-guild-scheduled-event-entity-metadata
     * @see https://discord.com/developers/docs/resources/guild-scheduled-event#guild-scheduled-event-object-field-requirements-by-entity-type
     */
    protected array $entity_metadata;
    /**
     * the user that created the scheduled event
     */
    protected ?User $creator;
    /**
     * the number of users subscribed to the scheduled event
     */
    protected int $user_count;
    /**
     * the cover image hash of the scheduled event
     */
    protected ?string $image;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->guild_id = new Snowflake($data['guild_id']);
        $this->channel_id = !empty($data['channel_id']) ? new Snowflake($data['channel_id']) : null;
        $this->creator_id = !empty($data['creator_id']) ? new Snowflake($data['creator_id']) : null;
        $this->name = $data['name'];
        $this->description = $data['description'] ?? '';
        $this->scheduled_start_time = strtotime($data['scheduled_start_time']);
        $this->scheduled_end_time = !empty($data['scheduled_end_time']) ? strtotime($data['scheduled_end_time']) : 0;
        $this->privacy_level = $data['privacy_level'];
        $this->status = $data['status'];
        $this->entity_type = $data['entity_type'];
        $this->entity_id = !empty($data['entity_id']) ? new Snowflake($data['entity_id']) : null;
        $this->entity_metadata = $data['entity_metadata'] ?? [];
        $this->creator = !empty($data['creator']) ? new User($data['creator']) : null;
        $this->user_count = $data['user_count'] ?? 0;
        $this->image = $data['image'] ?? null;
    }

    /**
     * the id of the scheduled event
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * the guild id which the scheduled event belongs to
     * @return Snowflake
     */
    public function getGuildId(): Snowflake
    {
        return $this->guild_id;
    }

    /**
     * the channel id in which the scheduled event will be hosted, or null if scheduled entity type is EXTERNAL
     * @return ?Snowflake
     */
    public function getChannelId(): ?Snowflake
    {
        return $this->channel_id;
    }

    /**
     * the id of the user that created the scheduled event
     * @return ?Snowflake
     */
    public function getCreatorId(): ?Snowflake
    {
        return $this->creator_id;
    }

    /**
     * the name of the scheduled event (1-100 characters)
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * the description of the scheduled event (1-1000 characters)
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * the time the scheduled event will start
     * @return int
     */
    public function getScheduledStartTime(): int
    {
        return $this->scheduled_start_time;
    }

    /**
     * the time the scheduled event will end, required if entity_type is EXTERNAL
     * @return int
     */
    public function getScheduledEndTime(): int
    {
        return $this->scheduled_end_time;
    }

    /**
     * the privacy level of the scheduled event
     * @return int
     */
    public function getPrivacyLevel(): int
    {
        return $this->privacy_level;
    }

    /**
     * the status of the scheduled event
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * the type of the scheduled event
     * @return int
     */
    public function getEntityType(): int
    {
        return $this->entity_type;
    }

    /**
     * the id of an entity associated with a guild scheduled event
     * @return ?Snowflake
     */
    public function getEntityId(): ?Snowflake
    {
        return $this->entity_id;
    }

    /**
     * additional metadata for the guild scheduled event
     * @return array
     */
    public function getEntityMetadata(): array
    {
        return $this->entity_metadata;
    }

    /**
     * the user that created the scheduled event
     * @return ?User
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * the number of users subscribed to the scheduled event
     * @return int
     */
    public function getUserCount(): int
    {
        return $this->user_count;
    }

    /**
     * the cover image hash of the scheduled event
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }


}