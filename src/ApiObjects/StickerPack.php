<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class StickerPack
{
    /**
     * id of the sticker pack
     */
    protected Snowflake $id;
    /**
     * the stickers in the pack
     * @var Sticker[]
     */
    protected array $stickers;
    /**
     * name of the sticker pack
     */
    protected string $name;
    /**
     * id of the pack's SKU
     */
    protected Snowflake $sku_id;
    /**
     * id of a sticker in the pack which is shown as the pack's icon
     */
    protected ?Snowflake $cover_sticker_id;
    /**
     * description of the sticker pack
     */
    protected string $description;
    /**
     * id of the sticker pack's banner image
     */
    protected Snowflake $banner_asset_id;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->sku_id = new Snowflake($data['sku_id']);
        $this->cover_sticker_id = !empty($data['cover_sticker_id']) ? new Snowflake($data['cover_sticker_id']) : null;
        $this->description = $data['description'];
        $this->banner_asset_id = new Snowflake($data['banner_asset_id']);

        $this->stickers = array_map(fn(array $stickers) => new Sticker($data), $data['stickers'] ?? []);
    }

    /**
     * id of the sticker pack
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * the stickers in the pack
     *
     * @return Sticker[]
     */
    public function getStickers(): array
    {
        return $this->stickers;
    }

    /**
     * name of the sticker pack
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * id of the pack's SKU
     */
    public function getSkuId(): Snowflake
    {
        return $this->sku_id;
    }

    /**
     * id of a sticker in the pack which is shown as the pack's icon
     */
    public function getCoverStickerId(): ?Snowflake
    {
        return $this->cover_sticker_id;
    }

    /**
     * description of the sticker pack
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * id of the sticker pack's banner image
     */
    public function getBannerAssetId(): Snowflake
    {
        return $this->banner_asset_id;
    }
}