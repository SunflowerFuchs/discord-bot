<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


class EmbedVideo
{
    /**
     * source url of video
     */
    protected ?string $url;
    /**
     * height of video
     */
    protected ?int $height;
    /**
     * width of video
     */
    protected ?int $width;

    public function __construct(array $data)
    {
        $this->url = $data['url'] ?? null;
        $this->height = $data['height'] ?? null;
        $this->width = $data['width'] ?? null;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return ?int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return ?int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }


}