<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class Attachment
{
    /**
     * attachment id
     */
    protected Snowflake $id;
    /**
     * name of file attached
     */
    protected string $filename;
    /**
     * size of file in bytes
     */
    protected int $size;
    /**
     * source url of file
     */
    protected string $url;
    /**
     * a proxied url of file
     */
    protected string $proxy_url;
    /**
     * height of file (if image)
     */
    protected ?int $height;
    /**
     * width of file (if image)
     */
    protected ?int $width;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->filename = $data['filename'];
        $this->size = intval($data['size']);
        $this->url = $data['url'];
        $this->proxy_url = $data['proxy_url'];
        $this->height = !empty($data['height']) ? intval($data['height']) : null;
        $this->width = !empty($data['width']) ? intval($data['width']) : null;
    }

    /**
     * Id of the attachment
     *
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * Name of the file
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Filesize in bytes
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * The source url of the file
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * A proxied url for the file
     *
     * @return string
     */
    public function getProxyUrl(): string
    {
        return $this->proxy_url;
    }

    /**
     * Height of the attachment, if image
     *
     * @return ?int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * Width of the attachment, if image
     *
     * @return ?int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }


}