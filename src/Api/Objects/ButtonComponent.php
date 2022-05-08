<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\ButtonStyle;

class ButtonComponent extends Component
{
    public const TYPE = 2;

    /**
     * whether the component is disabled, default false
     */
    protected bool $disabled = false;
    /**
     * one of button styles
     */
    protected int $style = ButtonStyle::PRIMARY;
    /**
     * text that appears on the button, max 80 characters
     */
    protected string $label = '';
    /**
     * partial emoji (name, id, and animated)
     */
    protected ?Emoji $emoji = null;
    /**
     * a url for link-style buttons
     */
    protected ?string $url = null;

    public static function fromData(array $data): self
    {
        $that = parent::fromData($data);

        $that->setDisabled($data['disabled'] ?? false);
        $that->setLabel($data['label'] ?? '');
        $that->setStyle($data['style']);

        if (!empty($data['emoji'])) {
            $that->setEmoji(new Emoji($data['emoji']));
        }

        if (!empty($data['url'])) {
            $that->setUrl($data['url']);
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
     * one of button styles
     */
    public function getStyle(): ?int
    {
        return $this->style;
    }

    public function setStyle(int $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * text that appears on the button, max 80 characters
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
     * a url for link-style buttons
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the URL for a LINK-style button
     *
     * Automatically sets the style to LINK
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        $this->style = ButtonStyle::LINK;
        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['label'] = $this->getLabel();
        $array['disabled'] = $this->isDisabled();

        $array['style'] = $this->getStyle();
        if ($this->getStyle() === ButtonStyle::LINK) {
            $array['url'] = $this->getUrl();
            unset($array['custom_id']);
        }

        $array['emoji'] = null;
        if ($this->getEmoji() !== null) {
            $array['emoji'] = array_filter(
                $this->getEmoji()->toArray(),
                fn(string $key) => in_array($key, ['id', 'name', 'animated']),
                ARRAY_FILTER_USE_KEY
            );
        }

        return $array;
    }
}