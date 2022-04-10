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

    public function __construct(array $data)
    {
        $this->type = $data['type'];
    }

    public function getType(): int
    {
        return $this->type;
    }
}