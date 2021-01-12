<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


use SunflowerFuchs\DiscordBot\Bot;

class MessageApplication
{
    /**
     * id of the application
     */
    protected Snowflake $id;
    /**
     * id of the embed's image asset
     */
    protected ?string $cover_image;
    /**
     * application's description
     */
    protected string $description;
    /**
     * id of the application's icon
     */
    protected ?string $icon;
    /**
     * name of the application
     */
    protected string $name;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->cover_image = $data['cover_image'] ?? null;
        $this->description = $data['description'];
        $this->icon = $data['icon'] ?? null;
        $this->name = $data['name'];
    }

    /**
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * get the icons id
     * @return ?string
     */
    public function getIconId(): ?string
    {
        return $this->icon;
    }

    /**
     * Shortcut to get the url of the applications icon
     *
     * @param string $format
     * @param int $size
     * @return ?string
     */
    public function getIconUrl(string $format = 'png', int $size = 1024): ?string
    {
        $baseUrl = Bot::BaseImageUrl;
        $applicationId = $this->getId();
        $iconId = $this->getIconId();
        if (!$iconId) {
            return null;
        }

        // validate the file type
        if (!in_array($format, ['png', 'jpg', 'jpeg', 'webp'])) {
            return null;
        }

        // make sure the size is within the allowed range
        $size = max(min($size, 4096), 16);

        return "${baseUrl}app-icons/${applicationId}/${iconId}.${format}?size=${size}";
    }

    /**
     * the name of the application
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}