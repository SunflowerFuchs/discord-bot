<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class ButtonComponent extends Component
{
    public const TYPE = 2;

    /**
     * a developer-defined identifier for the component, max 100 characters
     */
    protected ?string $custom_id;
    /**
     * whether the component is disabled, default false
     */
    protected bool $disabled;
    /**
     * one of button styles
     */
    protected ?int $style;
    /**
     * text that appears on the button, max 80 characters
     */
    protected ?string $label;
    /**
     * name, id, and animated
     */
    protected ?Emoji $emoji;
    /**
     * a url for link-style buttons
     */
    protected ?string $url;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->custom_id = $data['custom_id'] ?? null;
        $this->disabled = $data['disabled'] ?? false;
        $this->style = $data['style'] ?? null;
        $this->label = $data['label'] ?? null;
        $this->emoji = !empty($data['emoji']) ? new Emoji($data['emoji']) : null;
        $this->url = $data['url'] ?? null;
    }

    /**
     * a developer-defined identifier for the component, max 100 characters
     */
    public function getCustomId(): ?string
    {
        return $this->custom_id;
    }

    /**
     * whether the component is disabled, default false
     */
    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    /**
     * one of button styles
     */
    public function getStyle(): ?int
    {
        return $this->style;
    }

    /**
     * text that appears on the button, max 80 characters
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * partial emoji object
     */
    public function getEmoji(): ?Emoji
    {
        return $this->emoji;
    }

    /**
     * a url for link-style buttons
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}