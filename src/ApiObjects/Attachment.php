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
     * description for the file
     */
    protected string $description;
    /**
     * the attachment's media type
     */
    protected string $content_type;
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
    protected int $height;
    /**
     * width of file (if image)
     */
    protected int $width;
    /**
     * whether this attachment is ephemeral
     *
     * Ephemeral attachments will automatically be removed after a set period of time.
     * Ephemeral attachments on messages are guaranteed to be available as long as the message itself exists.
     */
    protected bool $ephemeral;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->filename = $data['filename'];
        $this->description = $data['description'] ?? '';
        $this->content_type = $data['content_type'] ?? '';
        $this->size = intval($data['size']);
        $this->url = $data['url'];
        $this->proxy_url = $data['proxy_url'];
        $this->height = !empty($data['height']) ? intval($data['height']) : 0;
        $this->width = !empty($data['width']) ? intval($data['width']) : 0;
        $this->ephemeral = $data['ephemeral'] ?? false;
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
     * description for the file
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * the attachment's media type
     */
    public function getContentType(): string
    {
        return $this->content_type;
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
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Width of the attachment, if image
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * whether this attachment is ephemeral
     *
     * Ephemeral attachments will automatically be removed after a set period of time.
     * Ephemeral attachments on messages are guaranteed to be available as long as the message itself exists.
     * @return bool
     */
    public function isEphemeral(): bool
    {
        return $this->ephemeral;
    }
}