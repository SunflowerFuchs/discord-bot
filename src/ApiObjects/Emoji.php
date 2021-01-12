<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


use SunflowerFuchs\DiscordBot\Bot;

class Emoji
{
    /**
     * emoji id
     */
    protected ?Snowflake $id;
    /**
     * (can be null only in reaction emoji objects)    emoji name
     */
    protected ?string $name;
    /**
     * role ids this emoji is whitelisted to
     * @var int[]
     */
    protected ?array $roles;
    /**
     * object user that created this emoji
     */
    protected ?User $user;
    /**
     * whether this emoji must be wrapped in colons
     */
    protected ?bool $require_colons;
    /**
     * whether this emoji is managed
     */
    protected ?bool $managed;
    /**
     * whether this emoji is animated
     */
    protected ?bool $animated;
    /**
     * whether this emoji can be used, may be false due to loss of Server Boosts
     */
    protected ?bool $available;

    public function __construct(array $data)
    {
        $this->id = !empty($data['id']) ? new Snowflake($data['id']) : null;
        $this->name = $data['name'] ?? null;
        $this->roles = $data['roles'] ?? [];
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->require_colons = $data['require_colons'] ?? true;
        $this->managed = $data['managed'] ?? false;
        $this->animated = $data['animated'] ?? false;
        $this->available = $data['available'] ?? true;
    }

    /**
     * The id of the emote
     *
     * @return ?Snowflake
     */
    public function getId(): ?Snowflake
    {
        return $this->id;
    }

    /**
     * The name of the emote
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * The roles that are whitelisted to use the emoji
     *
     * @return int[]
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * The user that created the emoji
     *
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * If the emoji needs colons
     *
     * @return bool
     */
    public function getRequireColons(): ?bool
    {
        return $this->require_colons;
    }

    /**
     * If the image is managed by a bot/integration
     *
     * @return bool
     */
    public function isManaged(): ?bool
    {
        return $this->managed;
    }

    /**
     * Whether the emoji is animated
     *
     * @return bool
     */
    public function isAnimated(): ?bool
    {
        return $this->animated;
    }

    /**
     * Whether the emoji is available to use
     *
     * @return bool
     */
    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    /**
     * The image url of the emoji
     *
     * @param int $size
     * @return ?string
     */
    public function getUrl(int $size = 1024): ?string
    {
        $id = $this->getId();
        if (!$id) {
            return null;
        }

        // make sure the size is within the allowed range
        $size = max(min($size, 4096), 16);

        $baseUrl = Bot::BaseImageUrl;
        $ending = $this->isAnimated() ? 'gif' : 'png';
        return "${baseUrl}emojis/${id}.${ending}?size=${size}";
    }
}