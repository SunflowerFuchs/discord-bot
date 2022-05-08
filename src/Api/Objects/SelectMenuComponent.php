<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class SelectMenuComponent extends Component
{
    public const TYPE = 3;

    /**
     * whether the component is disabled, default false
     */
    protected bool $disabled = false;
    /**
     * the choices in the select, max 25
     * @var SelectOption[]
     */
    protected array $options = [];
    /**
     * custom placeholder text if nothing is selected, max 100 characters
     */
    protected string $placeholder = '';
    /**
     * the minimum number of items that must be chosen; default 1, min 0, max 25
     */
    protected int $min_values = 1;
    /**
     * the maximum number of items that can be chosen; default 1, max 25
     */
    protected int $max_values = 1;

    public static function fromData(array $data): self
    {
        $that = parent::fromData($data);

        $that->setDisabled($data['disabled'] ?? false);
        $that->setPlaceholder($data['placeholder'] ?? '');
        $that->setMinValues($data['min_values'] ?? 1);
        $that->setMaxValues($data['max_values'] ?? 1);

        foreach ($data['options'] as $optionData) {
            $that->addOption(SelectOption::fromData($optionData));
        }

        return $that;
    }

    /**
     * whether the component is disabled, default false
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * the choices in the select, max 25
     * @return SelectOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function addOption(SelectOption $option): self
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * custom placeholder text if nothing is selected, max 100 characters
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * the minimum number of items that must be chosen; default 1, min 0, max 25
     */
    public function getMinValues(): int
    {
        return $this->min_values;
    }

    public function setMinValues(int $minValues): self
    {
        $this->min_values = $minValues;
        return $this;
    }

    /**
     * the maximum number of items that can be chosen; default 1, max 25
     */
    public function getMaxValues(): int
    {
        return $this->max_values;
    }

    public function setMaxValues(int $maxValues): self
    {
        $this->max_values = $maxValues;
        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['disabled'] = $this->isDisabled();
        $array['placeholder'] = $this->getPlaceholder();
        $array['min_values'] = $this->getMinValues();
        $array['max_values'] = $this->getMaxValues();

        $array['options'] = [];
        foreach ($this->options as $option) {
            $array['options'][] = $option->toArray();
        }

        return $array;
    }
}