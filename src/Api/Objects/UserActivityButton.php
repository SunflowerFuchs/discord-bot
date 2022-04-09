<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class UserActivityButton
{
    /**
     * the text shown on the button (1-32 characters)
     */
    protected string $label;
    /**
     * the url opened when clicking the button (1-512 characters)
     */
    protected string $url;

    public function __construct(array $data)
    {
        $this->label = $data['label'];
        $this->url = $data['url'];
    }

    /**
     * the text shown on the button (1-32 characters)
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * the url opened when clicking the button (1-512 characters)
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}