<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\TextInputStyle;

class TextInputComponent extends Component
{
    public const TYPE = 4;

    /**
     * the Text Input Style
     * @see TextInputStyle
     */
    protected int $style = TextInputStyle::SHORT;
    /**
     * the label for this component, max 45 characters
     */
    protected string $label = '';
    /**
     * the minimum input length for a text input, min 0, max 4000
     */
    protected int $min_length = 0;
    /**
     * the maximum input length for a text input, min 1, max 4000
     */
    protected int $max_length = 4000;
    /**
     * whether this component is required to be filled, default true
     */
    protected bool $required = true;
    /**
     * a pre-filled value for this component, max 4000 characters
     */
    protected string $value = '';
    /**
     * custom placeholder text if the input is empty, max 100 characters
     */
    protected string $placeholder = '';

    public static function fromData(array $data): self
    {
        $that = parent::fromData($data);

        $that->setStyle($data['style']);
        $that->setLabel($data['label']);
        $that->setMinLength($data['min_length'] ?? 0);
        $that->setMaxLength($data['max_length'] ?? 4000);
        $that->setRequired($data['required'] ?? true);
        $that->setValue($data['value'] ?? '');
        $that->setPlaceholder($data['placeholder'] ?? '');

        return $that;
    }

    /**
     * the Text Input Style
     * @return int
     */
    public function getStyle(): int
    {
        return $this->style;
    }

    public function setStyle(int $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * the label for this component, max 45 characters
     * @return string
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
     * the minimum input length for a text input, min 0, max 4000
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->min_length;
    }

    public function setMinLength(int $minLength): self
    {
        $this->min_length = $minLength;
        return $this;
    }

    /**
     * the maximum input length for a text input, min 1, max 4000
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->max_length;
    }

    public function setMaxLength(int $maxLength): self
    {
        $this->max_length = $maxLength;
        return $this;
    }

    /**
     * whether this component is required to be filled, default true
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * a pre-filled value for this component, max 4000 characters
     * @return string
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
     * custom placeholder text if the input is empty, max 100 characters
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array ['style'] = $this->getStyle();
        $array ['label'] = $this->getLabel();
        $array ['min_length'] = $this->getMinLength();
        $array ['max_length'] = $this->getMaxLength();
        $array ['required'] = $this->isRequired();
        $array ['value'] = $this->getValue();
        $array ['placeholder'] = $this->getPlaceholder();
        return $array;
    }
}