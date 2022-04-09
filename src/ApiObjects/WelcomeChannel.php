<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class WelcomeChannel
{
    /**
     * the channel's id
     */
    protected Snowflake $channel_id;
    /**
     * the description shown for the channel
     */
    protected string $description;
    /**
     * the emoji id, if the emoji is custom
     */
    protected ?Snowflake $emoji_id;
    /**
     * the emoji name if custom, the unicode character if standard, or null if no emoji is set
     */
    protected ?string $emoji_name;

    public function __construct(array $data)
    {
        $this->channel_id = new Snowflake($data['channel_id']);
        $this->description = $data['description'];
        $this->emoji_id = !empty($data['emoji_id']) ? new Snowflake($data['emoji_id']) : null;
        $this->emoji_name = $data['emoji_name'] ?? null;
    }

    /**
     * the channel's id
     * @return Snowflake
     */
    public function getChannelId(): Snowflake
    {
        return $this->channel_id;
    }

    /**
     * the description shown for the channel
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * the emoji id, if the emoji is custom
     * @return ?Snowflake
     */
    public function getEmojiId(): ?Snowflake
    {
        return $this->emoji_id;
    }

    /**
     * the emoji name if custom, the unicode character if standard, or null if no emoji is set
     * @return ?string
     */
    public function getEmojiName(): ?string
    {
        return $this->emoji_name;
    }
}