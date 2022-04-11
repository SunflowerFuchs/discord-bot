<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\TextInputStyle;

class TextInputComponent extends Component
{
    public const TYPE = 4;

    /**
     * a developer-defined identifier for the input, max 100 characters
     */
    protected string $custom_id;
    /**
     * the Text Input Style
     * @see TextInputStyle
     */
    protected int $style;
    /**
     * the label for this component, max 45 characters
     */
    protected string $label;
    /**
     * the minimum input length for a text input, min 0, max 4000
     */
    protected int $min_length;
    /**
     * the maximum input length for a text input, min 1, max 4000
     */
    protected int $max_length;
    /**
     * whether this component is required to be filled, default true
     */
    protected bool $required;
    /**
     * a pre-filled value for this component, max 4000 characters
     */
    protected string $value;
    /**
     * custom placeholder text if the input is empty, max 100 characters
     */
    protected string $placeholder;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->custom_id = $data['custom_id'];
        $this->style = $data['style'];
        $this->label = $data['label'];
        $this->min_length = $data['min_length'] ?? 0;
        $this->max_length = $data['max_length'] ?? 4000;
        $this->required = $data['required'] ?? true;
        $this->value = $data['value'] ?? '';
        $this->placeholder = $data['placeholder'] ?? '';
    }

    /**
     * a developer-defined identifier for the input, max 100 characters
     * @return string
     */
    public function getCustomId(): string
    {
        return $this->custom_id;
    }

    /**
     * the Text Input Style
     * @return int
     */
    public function getStyle(): int
    {
        return $this->style;
    }

    /**
     * the label for this component, max 45 characters
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * the minimum input length for a text input, min 0, max 4000
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->min_length;
    }

    /**
     * the maximum input length for a text input, min 1, max 4000
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->max_length;
    }

    /**
     * whether this component is required to be filled, default true
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * a pre-filled value for this component, max 4000 characters
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * custom placeholder text if the input is empty, max 100 characters
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }


}