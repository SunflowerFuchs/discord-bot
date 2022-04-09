<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\ApplicationFlag;

class Application
{
    /**
     * the id of the app
     */
    protected Snowflake $id;
    /**
     * the name of the app
     */
    protected string $name;
    /**
     * the icon hash of the app
     */
    protected ?string $icon;
    /**
     * the description of the app
     */
    protected string $description;
    /**
     * an array of rpc origin urls, if rpc is enabled
     * @var string[]
     */
    protected array $rpc_origins;
    /**
     * when false only app owner can join the app's bot to guilds
     */
    protected bool $bot_public;
    /**
     * when true the app's bot will only join upon completion of the full oauth2 code grant flow
     */
    protected bool $bot_require_code_grant;
    /**
     * the url of the app's terms of service
     */
    protected ?string $terms_of_service_url;
    /**
     * the url of the app's privacy policy
     */
    protected ?string $privacy_policy_url;
    /**
     * partial user object containing info on the owner of the application
     */
    protected ?User $owner;
    /**
     * the hex encoded key for verification in interactions and the GameSDK's GetTicket
     */
    protected string $verify_key;
    /**
     * if the application belongs to a team, this will be a list of the members of that team
     */
    protected ?Team $team;
    /**
     * if this application is a game sold on Discord, this field will be the guild to which it has been linked
     */
    protected ?Snowflake $guild_id;
    /**
     * if this application is a game sold on Discord, this field will be the id of the "Game SKU" that is created, if exists
     */
    protected ?Snowflake $primary_sku_id;
    /**
     * if this application is a game sold on Discord, this field will be the URL slug that links to the store page
     */
    protected ?string $slug;
    /**
     * the application's default rich presence invite cover image hash
     */
    protected ?string $cover_image;
    /**
     * the application's public flags
     * @see ApplicationFlag
     */
    protected int $flags;
    /**
     * up to 5 tags describing the content and functionality of the application
     * @var string[]
     */
    protected array $tags;
    /**
     * settings for the application's default in-app authorization link, if enabled
     */
    protected InstallParams $install_params;
    /**
     * the application's default custom authorization link, if enabled
     */
    protected ?string $custom_install_url;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->icon = $data['icon'] ?? null;
        $this->description = $data['description'];
        $this->rpc_origins = $data['rpc_origins'] ?? [];
        $this->bot_public = $data['bot_public'];
        $this->bot_require_code_grant = $data['bot_require_code_grant'];
        $this->terms_of_service_url = $data['terms_of_service_url'] ?? null;
        $this->privacy_policy_url = $data['privacy_policy_url'] ?? null;
        $this->owner = !empty($data['owner']) ? new User($data['owner']) : null;
        $this->verify_key = $data['verify_key'];
        $this->team = !empty($data['team']) ? new Team($data['team']) : null;
        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->primary_sku_id = !empty($data['primary_sku_id']) ? new Snowflake($data['primary_sku_id']) : null;
        $this->slug = $data['slug'] ?? null;
        $this->cover_image = $data['cover_image'] ?? null;
        $this->flags = $data['flags'] ?? 0;
        $this->tags = $data['tags'] ?? [];
        $this->install_params = !empty($data['install_params']) ? new InstallParams($data['install_params']) : null;
        $this->custom_install_url = $data['custom_install_url'] ?? null;
    }

    /**
     * the id of the app
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * the name of the app
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * the icon hash of the app
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * the description of the app
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * an array of rpc origin urls, if rpc is enabled
     * @return string[]
     */
    public function getRpcOrigins(): array
    {
        return $this->rpc_origins;
    }

    /**
     * when false only app owner can join the app's bot to guilds
     * @return bool
     */
    public function isBotPublic(): bool
    {
        return $this->bot_public;
    }

    /**
     * when true the app's bot will only join upon completion of the full oauth2 code grant flow
     * @return bool
     */
    public function isBotRequireCodeGrant(): bool
    {
        return $this->bot_require_code_grant;
    }

    /**
     * the url of the app's terms of service
     * @return ?string
     */
    public function getTermsOfServiceUrl(): ?string
    {
        return $this->terms_of_service_url;
    }

    /**
     * the url of the app's privacy policy
     * @return ?string
     */
    public function getPrivacyPolicyUrl(): ?string
    {
        return $this->privacy_policy_url;
    }

    /**
     * partial user object containing info on the owner of the application
     * @return ?User
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * the hex encoded key for verification in interactions and the GameSDK's GetTicket
     * @return string
     */
    public function getVerifyKey(): string
    {
        return $this->verify_key;
    }

    /**
     * if the application belongs to a team, this will be a list of the members of that team
     * @return ?Team
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * if this application is a game sold on Discord, this field will be the guild to which it has been linked
     * @return ?Snowflake
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * if this application is a game sold on Discord, this field will be the id of the "Game SKU" that is created, if exists
     * @return ?Snowflake
     */
    public function getPrimarySkuId(): ?Snowflake
    {
        return $this->primary_sku_id;
    }

    /**
     * if this application is a game sold on Discord, this field will be the URL slug that links to the store page
     * @return ?string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * the application's default rich presence invite cover image hash
     * @return ?string
     */
    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    /**
     * the application's public flags
     * @return int
     * @see ApplicationFlag
     */
    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * up to 5 tags describing the content and functionality of the application
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * settings for the application's default in-app authorization link, if enabled
     * @return InstallParams
     */
    public function getInstallParams(): InstallParams
    {
        return $this->install_params;
    }

    /**
     * the application's default custom authorization link, if enabled
     * @return ?string
     */
    public function getCustomInstallUrl(): ?string
    {
        return $this->custom_install_url;
    }


}