<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


class   MessageReference
{
    /**
     * id of the originating message
     */
    protected ?Snowflake $message_id;
    /**
     * id of the originating message's channel
     */
    protected ?Snowflake $channel_id;
    /**
     * id of the originating message's guild
     */
    protected ?Snowflake $guild_id;
    /**
     * when sending, whether to error if the referenced message doesn't exist
     * instead of sending as a normal (non-reply) message, default true
     */
    protected ?bool $fail_if_not_exists;

    public function __construct(array $data)
    {
        $this->message_id = !empty($data['message_id']) ? new Snowflake($data['message_id']) : null;
        $this->channel_id = !empty($data['channel_id']) ? new Snowflake($data['channel_id']) : null;
        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->fail_if_not_exists = $data['fail_if_not_exists'] ?? true;
    }

    /**
     * id of the originating message
     * @return ?Snowflake
     */
    public function getMessageId(): ?Snowflake
    {
        return $this->message_id;
    }

    /**
     * id of the originating message's channel
     * @return ?Snowflake
     */
    public function getChannelId(): ?Snowflake
    {
        return $this->channel_id;
    }

    /**
     * id of the originating message's guild
     * @return ?Snowflake
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * when sending, whether to error if the referenced message doesn't exist
     * instead of sending as a normal (non-reply) message, default true
     */
    public function getFailIfNotExists(): bool
    {
        return $this->fail_if_not_exists;
    }
}