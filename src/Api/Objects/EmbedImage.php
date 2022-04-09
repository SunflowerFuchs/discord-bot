<?php


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class EmbedImage
{
    /**
     * source url of image (only supports http(s) and attachments)
     */
    protected ?string $url;
    /**
     * a proxied url of the image
     */
    protected ?string $proxy_url;
    /**
     * height of image
     */
    protected ?int $height;
    /**
     * width of image
     */
    protected ?int $width;

    public function __construct(array $data)
    {
        $this->url = $data['url'] ?? null;
        $this->proxy_url = $data['proxy_url'] ?? null;
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
     * @return ?string
     */
    public function getProxyUrl(): ?string
    {
        return $this->proxy_url;
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