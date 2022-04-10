<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class StickerItem
{
    const FORMAT_TYPE_PNG = 1;
    const FORMAT_TYPE_APNG = 2;
    const FORMAT_TYPE_LOTTIE = 3;

    /**
     * id of the sticker
     */
    protected Snowflake $id;
    /**
     * name of the sticker
     */
    protected string $name;
    /**
     * type of sticker format
     */
    protected int $format_type;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->format_type = $data['format_type'];
    }

    /**
     * id of the sticker
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * name of the sticker
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * type of sticker format
     *
     * @see StickerItem::FORMAT_TYPE_PNG
     * @see StickerItem::FORMAT_TYPE_APNG
     * @see StickerItem::FORMAT_TYPE_LOTTIE
     */
    public function getFormatType(): int
    {
        return $this->format_type;
    }
}