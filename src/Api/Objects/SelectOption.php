<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class SelectOption
{
    /**
     * the user-facing name of the option, max 100 characters
     */
    protected string $label = '';
    /**
     * the dev-define value of the option, max 100 characters
     */
    protected string $value = '';
    /**
     * an additional description of the option, max 100 characters
     */
    protected string $description = '';
    /**
     * partial emoji object (name, id, animated)
     */
    protected ?Emoji $emoji = null;
    /**
     * will render this option as selected by default
     */
    protected bool $default = false;

    public static function fromData(array $data): self
    {
        $that = new static();
        $that->setLabel($data['label']);
        $that->setValue($data['value']);
        $that->setDescription($data['description'] ?? '');
        $that->setEmoji(!empty($data['emoji']) ? new Emoji($data['emoji']) : null);
        $that->setDefault($data['default'] ?? false);
        return $that;
    }

    /**
     * the user-facing name of the option, max 100 characters
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * the dev-define value of the option, max 100 characters
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * an additional description of the option, max 100 characters
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * partial emoji object
     */
    public function getEmoji(): ?Emoji
    {
        return $this->emoji;
    }

    public function setEmoji(?Emoji $emoji): self
    {
        $this->emoji = $emoji;
        return $this;
    }

    /**
     * will render this option as selected by default
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $default): self
    {
        $this->default = $default;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'description' => $this->getDescription(),
            'default' => $this->isDefault(),
            'emoji' => $this->getEmoji() ? array_filter(
                $this->getEmoji()->toArray(),
                fn(string $key) => in_array($key, ['id', 'name', 'animated']),
                ARRAY_FILTER_USE_KEY
            ) : null
        ];
    }
}