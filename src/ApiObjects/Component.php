<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

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