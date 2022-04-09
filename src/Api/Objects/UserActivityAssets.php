<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class UserActivityAssets
{
    /**
     * see Activity Asset Image
     */
    protected string $large_image;
    /**
     * text displayed when hovering over the large image of the activity
     */
    protected string $large_text;
    /**
     * see Activity Asset Image
     */
    protected string $small_image;
    /**
     * text displayed when hovering over the small image of the activity
     */
    protected string $small_text;

    public function __construct(array $data)
    {
        $this->large_image = $data['large_image'] ?? '';
        $this->large_text = $data['large_text'] ?? '';
        $this->small_image = $data['small_image'] ?? '';
        $this->small_text = $data['small_text'] ?? '';
    }

    /**
     * see Activity Asset Image
     * @return string
     */
    public function getLargeImage(): string
    {
        return $this->large_image;
    }

    /**
     * text displayed when hovering over the large image of the activity
     * @return string
     */
    public function getLargeText(): string
    {
        return $this->large_text;
    }

    /**
     * see Activity Asset Image
     * @return string
     */
    public function getSmallImage(): string
    {
        return $this->small_image;
    }

    /**
     * text displayed when hovering over the small image of the activity
     * @return string
     */
    public function getSmallText(): string
    {
        return $this->small_text;
    }
}