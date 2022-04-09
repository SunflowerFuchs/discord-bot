<?php


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class Sticker extends StickerItem
{
    /**
     * an official sticker in a pack, part of Nitro or in a removed purchasable pack
     */
    public const TYPE_STANDARD = 0;
    /**
     * a sticker uploaded to a Boosted guild for the guild's members
     */
    public const TYPE_GUILD = 1;

    /**
     * id of the pack the sticker is from
     */
    protected Snowflake $pack_id;
    /**
     * description of the sticker
     */
    protected string $description;
    /**
     * a list of tags for the sticker
     */
    protected array $tags;
    /**
     * type of sticker
     */
    protected int $type;
    /**
     * whether this guild sticker can be used, may be false due to loss of Server Boosts
     */
    protected ?bool $available;
    /**
     * id of the guild that owns this sticker
     */
    protected ?Snowflake $guild_id;
    /**
     * the user that uploaded the guild sticker
     */
    protected ?User $user;
    /**
     * the standard sticker's sort order within its pack
     */
    protected ?int $sort_value;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->pack_id = new Snowflake($data['pack_id']);
        $this->description = $data['description'];
        $this->tags = explode(',', $data['tags']);
        $this->type = $data['type'];

        // defaults to true on standard stickers, and to false on guild stickers
        $this->available = $data['available'] ?? ($this->type === self::TYPE_STANDARD);
        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->sort_value = $data['sort_value'] ?? null;
    }

    /**
     * id of the pack the sticker is from
     */
    public function getPackId(): Snowflake
    {
        return $this->pack_id;
    }

    /**
     * description of the sticker
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * a list of tags for the sticker
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * type of sticker
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * whether this guild sticker can be used, may be false due to loss of Server Boosts
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * id of the guild that owns this sticker
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * the user that uploaded the guild sticker
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * the standard sticker's sort order within its pack
     */
    public function getSortValue()
    {
        return $this->sort_value;
    }
}