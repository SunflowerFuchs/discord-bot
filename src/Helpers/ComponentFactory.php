<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use SunflowerFuchs\DiscordBot\Api\Objects\ActionRowComponent;
use SunflowerFuchs\DiscordBot\Api\Objects\ButtonComponent;
use SunflowerFuchs\DiscordBot\Api\Objects\Component;
use SunflowerFuchs\DiscordBot\Api\Objects\SelectMenuComponent;

class ComponentFactory
{
    const COMPONENT_TYPES = [
        Component::TYPE => Component::class,
        ActionRowComponent::TYPE => ActionRowComponent::class,
        ButtonComponent::TYPE => ButtonComponent::class,
        SelectMenuComponent::TYPE => SelectMenuComponent::class,
    ];

    public static function factory(array $data): Component
    {
        $class = static::COMPONENT_TYPES[$data['type']] ?? static::COMPONENT_TYPES[0];
        return new $class($data);
    }
}