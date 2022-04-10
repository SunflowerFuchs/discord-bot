<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class Embed
{
    /**
     * title of embed
     */
    protected ?string $title;
    /**
     * description of embed
     */
    protected ?string $description;
    /**
     * url of embed
     */
    protected ?string $url;
    /**
     * timestamp of embed content
     */
    protected ?int $timestamp;
    /**
     * color code of the embed
     */
    protected ?int $color;
    /**
     *  footer information
     */
    protected ?EmbedFooter $footer;
    /**
     * image information
     */
    protected ?EmbedImage $image;
    /**
     * thumbnail information
     */
    protected ?EmbedThumbnail $thumbnail;
    /**
     * video information
     */
    protected ?EmbedVideo $video;
    /**
     * provider information
     */
    protected ?EmbedProvider $provider;
    /**
     * author information
     */
    protected ?EmbedAuthor $author;
    /**
     * fields information
     * @var EmbedField[] $fields
     */
    protected array $fields;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->timestamp = !empty($data['timestamp']) ? strtotime($data['timestamp']) : null;
        $this->color = $data['color'] ?? 0;
        $this->footer = !empty($data['footer']) ? new EmbedFooter($data['footer']) : null;
        $this->image = !empty($data['image']) ? new EmbedImage($data['image']) : null;
        $this->thumbnail = !empty($data['thumbnail']) ? new EmbedThumbnail($data['thumbnail']) : null;
        $this->video = !empty($data['video']) ? new EmbedVideo($data['video']) : null;
        $this->provider = !empty($data['provider']) ? new EmbedProvider($data['provider']) : null;
        $this->author = !empty($data['author']) ? new EmbedAuthor($data['author']) : null;
        $this->fields = array_map(fn(array $field) => new EmbedField($field), $data['fields'] ?? []);
    }

    /**
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * Returns the color in int representation
     *
     * @return int
     */
    public function getColor(): int
    {
        return $this->color;
    }

    /**
     * Returns the color in hex representation
     *
     * @return string
     */
    public function getHexColor(): string
    {
        return str_pad(dechex($this->getColor()), 6, '0', STR_PAD_LEFT);
    }

    /**
     * @return ?EmbedFooter
     */
    public function getFooter(): ?EmbedFooter
    {
        return $this->footer;
    }

    /**
     * @return ?EmbedImage
     */
    public function getImage(): ?EmbedImage
    {
        return $this->image;
    }

    /**
     * @return ?EmbedThumbnail
     */
    public function getThumbnail(): ?EmbedThumbnail
    {
        return $this->thumbnail;
    }

    /**
     * @return ?EmbedVideo
     */
    public function getVideo(): ?EmbedVideo
    {
        return $this->video;
    }

    /**
     * @return ?EmbedProvider
     */
    public function getProvider(): ?EmbedProvider
    {
        return $this->provider;
    }

    /**
     * @return ?EmbedAuthor
     */
    public function getAuthor(): ?EmbedAuthor
    {
        return $this->author;
    }

    /**
     * @return EmbedField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }


}