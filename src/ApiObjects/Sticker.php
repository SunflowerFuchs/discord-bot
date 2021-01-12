<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


class Sticker
{
    const TYPE_PNG = 1;
    const TYPE_APNG = 2;
    const TYPE_LOTTIE = 3;

    /**
     * id of the sticker
     */
    protected Snowflake $id;
    /**
     * id of the pack the sticker is from
     */
    protected Snowflake $pack_id;
    /**
     * name of the sticker
     */
    protected string $name;
    /**
     * description of the sticker
     */
    protected string $description;
    /**
     * a list of tags for the sticker
     */
    protected array $tags;
    /**
     * sticker asset hash
     */
    protected string $asset;
    /**
     * sticker preview asset hash
     */
    protected ?string $preview_asset;
    /**
     * type of sticker format
     */
    protected int $format_type;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->pack_id = new Snowflake($data['pack_id']);
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->tags = !empty($data['tags']) ? explode(',', $data['tags']) : [];
        $this->asset = $data['asset'];
        $this->preview_asset = $data['preview_asset'] ?? null;
        $this->format_type = $data['format_type'];
    }

    /**
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * @return Snowflake
     */
    public function getPackId(): Snowflake
    {
        return $this->pack_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getAssetHash(): string
    {
        return $this->asset;
    }

    /**
     * @return ?string
     */
    public function getPreviewAsset(): ?string
    {
        return $this->preview_asset;
    }

    /**
     * @return int
     * @see Sticker::TYPE_PNG
     * @see Sticker::TYPE_APNG
     * @see Sticker::TYPE_LOTTIE
     */
    public function getFormatType(): int
    {
        return $this->format_type;
    }
}