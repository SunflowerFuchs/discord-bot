<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


class EmbedFooter
{
    /**
     * footer text
     */
    protected string $text;
    /**
     * url of footer icon (only supports http(s) and attachments)
     */
    protected ?string $icon_url;
    /**
     * a proxied url of footer icon
     */
    protected ?string $proxy_icon_url;

    public function __construct(array $data)
    {
        $this->text = $data['text'];
        $this->icon_url = $data['icon_url'] ?? null;
        $this->proxy_icon_url = $data['proxy_icon_url'] ?? null;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return ?string
     */
    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    /**
     * @return ?string
     */
    public function getProxyIconUrl(): ?string
    {
        return $this->proxy_icon_url;
    }
}