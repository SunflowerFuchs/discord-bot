<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class SelectMenuComponent extends Component
{
    public const TYPE = 3;

    /**
     * a developer-defined identifier for the component, max 100 characters
     */
    protected ?string $custom_id;
    /**
     * whether the component is disabled, default false
     */
    protected bool $disabled;
    /**
     * the choices in the select, max 25
     * @var SelectOption[]
     */
    protected array $options;
    /**
     * custom placeholder text if nothing is selected, max 100 characters
     */
    protected string $placeholder;
    /**
     * the minimum number of items that must be chosen; default 1, min 0, max 25
     */
    protected int $min_values;
    /**
     * the maximum number of items that can be chosen; default 1, max 25
     */
    protected int $max_values;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->custom_id = $data['custom_id'];
        $this->disabled = $data['disabled'] ?? false;
        $this->placeholder = $data['placeholder'] ?? '';
        $this->min_values = $data['min_values'] ?? 1;
        $this->max_values = $data['max_values'] ?? 1;

        $this->options = array_map(fn($option) => new SelectOption($option),
            $data['options']);
    }

    /**
     * a developer-defined identifier for the component, max 100 characters
     */
    public function getCustomId(): string
    {
        return $this->custom_id;
    }

    /**
     * whether the component is disabled, default false
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * the choices in the select, max 25
     * @return SelectOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * custom placeholder text if nothing is selected, max 100 characters
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    /**
     * the minimum number of items that must be chosen; default 1, min 0, max 25
     */
    public function getMinValues(): int
    {
        return $this->min_values;
    }

    /**
     * the maximum number of items that can be chosen; default 1, max 25
     */
    public function getMaxValues(): int
    {
        return $this->max_values;
    }
}