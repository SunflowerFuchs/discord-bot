<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class SelectOption
{
    /**
     * the user-facing name of the option, max 100 characters
     */
    protected string $label;
    /**
     * the dev-define value of the option, max 100 characters
     */
    protected string $value;
    /**
     * an additional description of the option, max 100 characters
     */
    protected ?string $description;
    /**
     * partial emoji object
     */
    protected ?Emoji $emoji;
    /**
     * will render this option as selected by default
     */
    protected bool $default;

    public function __construct(array $data)
    {
        $this->label = $data['label'];
        $this->value = $data['value'];
        $this->description = $data['description'] ?? null;
        $this->emoji = !empty($data['emoji']) ? new Emoji($data['emoji']) : null;
        $this->default = $data['default'] ?? false;
    }

    /**
     * the user-facing name of the option, max 100 characters
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * the dev-define value of the option, max 100 characters
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * an additional description of the option, max 100 characters
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * partial emoji object
     */
    public function getEmoji(): ?Emoji
    {
        return $this->emoji;
    }

    /**
     * will render this option as selected by default
     */
    public function isDefault(): bool
    {
        return $this->default;
    }
}