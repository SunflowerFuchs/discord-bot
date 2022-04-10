<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class EmbedProvider
{
    /**
     * name of provider
     */
    protected ?string $name;
    /**
     * url of provider
     */
    protected ?string $url;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->url = $data['url'] ?? null;
    }

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}