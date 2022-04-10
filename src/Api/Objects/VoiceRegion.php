<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class VoiceRegion
{
    /**
     * unique ID for the region
     */
    protected string $id;
    /**
     * name of the region
     */
    protected string $name;
    /**
     * true for a single server that is closest to the current user's client
     */
    protected bool $optimal;
    /**
     * whether this is a deprecated voice region (avoid switching to these)
     */
    protected bool $deprecated;
    /**
     * whether this is a custom voice region (used for events/etc)
     */
    protected bool $custom;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->optimal = $data['optimal'];
        $this->deprecated = $data['deprecated'];
        $this->custom = $data['custom'];
    }

    /**
     * unique ID for the region
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * name of the region
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * true for a single server that is closest to the current user's client
     * @return bool
     */
    public function isOptimal(): bool
    {
        return $this->optimal;
    }

    /**
     * whether this is a deprecated voice region (avoid switching to these)
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * whether this is a custom voice region (used for events/etc)
     * @return bool
     */
    public function isCustom(): bool
    {
        return $this->custom;
    }
}