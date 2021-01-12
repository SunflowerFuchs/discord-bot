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
     * whether or not this field should display inline
     */
    protected bool $inline;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->value = $data['value'];
        $this->inline = $data['inline'] ?? false;
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
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function getInline(): bool
    {
        return $this->inline;
    }
}