<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use SunflowerFuchs\DiscordBot\Api\Objects\ActionRowComponent;
use SunflowerFuchs\DiscordBot\Api\Objects\ButtonComponent;
use SunflowerFuchs\DiscordBot\Api\Objects\Component;
use SunflowerFuchs\DiscordBot\Api\Objects\SelectMenuComponent;
use SunflowerFuchs\DiscordBot\Api\Objects\TextInputComponent;

class ComponentFactory
{
    const COMPONENT_TYPES = [
        ActionRowComponent::TYPE => ActionRowComponent::class,
        ButtonComponent::TYPE => ButtonComponent::class,
        SelectMenuComponent::TYPE => SelectMenuComponent::class,
        TextInputComponent::TYPE => TextInputComponent::class,
    ];

    public static function factory(array $data): Component
    {
        $class = static::COMPONENT_TYPES[$data['type']] ?? static::COMPONENT_TYPES[0];
        return new $class($data);
    }
}