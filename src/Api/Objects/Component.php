<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

abstract class Component
{
    const TYPE = 0;

    /**
     * component type
     */
    protected int $type;
    /**
     * a developer-defined identifier for the component, max 100 characters
     */
    protected ?string $custom_id = null;

    public function __construct()
    {
        $this->setType(static::TYPE);
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data): self
    {
        $that = new static();
        $that->setType($data['type']);
        $that->setCustomId($data['custom_id'] ?? null);
        return $that;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * a developer-defined identifier for the component, max 100 characters
     */
    public function getCustomId(): ?string
    {
        return $this->custom_id;
    }

    public function setCustomId(?string $customId): self
    {
        $this->custom_id = $customId;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type
        ];
    }
}