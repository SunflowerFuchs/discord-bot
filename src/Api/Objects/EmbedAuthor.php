<?php


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class EmbedAuthor
{
    /**
     * name of author
     */
    protected ?string $name;
    /**
     * url of author
     */
    protected ?string $url;
    /**
     * url of author icon (only supports http(s) and attachments)
     */
    protected ?string $icon_url;
    /**
     * a proxied url of author icon
     */
    protected ?string $proxy_icon_url;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->icon_url = $data['icon_url'] ?? null;
        $this->proxy_icon_url = $data['proxy_icon_url'] ?? null;
    }

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
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