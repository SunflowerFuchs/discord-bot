<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


class EmbedField
{
    /**
     * name of the field
     */
    protected string $name;
    /**
     * value of the field
     */
    protected string $value;
    /**
     * whether this field should display inline
     */
    protected bool $inline;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->value = $data['value'];
        $this->inline = $data['inline'] ?? false;
    }

    /**
     * name of the field
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * value of the field
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * whether this field should display inline
     * @return bool
     */
    public function isInline(): bool
    {
        return $this->inline;
    }
}