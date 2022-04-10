<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class UserActivityEmoji
{
    /**
     * the name of the emoji
     */
    protected string $name;
    /**
     * the id of the emoji
     */
    protected ?Snowflake $id;
    /**
     * whether this emoji is animated
     */
    protected bool $animated;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->id = !empty($data['id']) ? new Snowflake($data['id']) : null;
        $this->animated = $data['animated'] ?? false;
    }

    /**
     * the name of the emoji
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * the id of the emoji
     * @return ?Snowflake
     */
    public function getId(): ?Snowflake
    {
        return $this->id;
    }

    /**
     * whether this emoji is animated
     * @return bool
     */
    public function isAnimated(): bool
    {
        return $this->animated;
    }
}