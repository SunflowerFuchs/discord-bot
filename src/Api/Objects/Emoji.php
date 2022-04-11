<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SunflowerFuchs\DiscordBot\Bot;

class Emoji
{
    /**
     * emoji id
     */
    protected ?Snowflake $id;
    /**
     * (can be null only in reaction emoji objects)    emoji name
     */
    protected ?string $name;
    /**
     * role ids this emoji is whitelisted to
     * @var int[]
     */
    protected ?array $roles;
    /**
     * object user that created this emoji
     */
    protected ?User $user;
    /**
     * whether this emoji must be wrapped in colons
     */
    protected ?bool $require_colons;
    /**
     * whether this emoji is managed
     */
    protected ?bool $managed;
    /**
     * whether this emoji is animated
     */
    protected ?bool $animated;
    /**
     * whether this emoji can be used, may be false due to loss of Server Boosts
     */
    protected ?bool $available;

    public function __construct(array $data)
    {
        $this->id = !empty($data['id']) ? new Snowflake($data['id']) : null;
        $this->name = $data['name'] ?? null;
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->require_colons = $data['require_colons'] ?? $this->isCustom();
        $this->managed = $data['managed'] ?? false;
        $this->animated = $data['animated'] ?? false;
        $this->available = $data['available'] ?? true;

        $this->roles = array_map(fn(array $roleData) => new Role($roleData),
            $data['roles'] ?? []);
    }

    /**
     * @param Client $apiClient
     * @param string $guildId
     * @return static[]
     * @throws GuzzleException
     */
    public static function listForGuild(Client $apiClient, string $guildId): array
    {
        $res = $apiClient->get("guilds/${guildId}/emojis");
        if ($res->getStatusCode() === 200) {
            return array_map(fn(array $emojiData) => new static($emojiData),
                json_decode($res->getBody()->getContents(), true));
        }

        return [];
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $emojiId
     * @return ?static
     * @throws GuzzleException
     */
    public static function loadById(Client $apiClient, Snowflake $guildId, Snowflake $emojiId): ?self
    {
        $res = $apiClient->get("guilds/${guildId}/emojis/${emojiId}");
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param string $name
     * @param string $imageData a data: URI for the image
     * @param Snowflake[] $roles
     * @param string $reason
     * @return static|null
     * @throws GuzzleException
     */
    public static function create(
        Client $apiClient,
        Snowflake $guildId,
        string $name,
        string $imageData,
        array $roles = [],
        string $reason = ''
    ): ?self {
        $params = [
            'name' => $name,
            'image' => $imageData,
            'roles' => $roles
        ];

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = ['X-Audit-Log-Reason' => $reason];
        }

        $res = $apiClient->post("guilds/${guildId}/emojis", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $emojiId
     * @param ?string $newName
     * @param ?Snowflake[] $roles
     * @param string $reason
     * @return static|null
     * @throws GuzzleException
     */
    public static function modify(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $emojiId,
        string $newName = null,
        array $roles = null,
        string $reason = ''
    ): ?self {
        $params = [];
        if (!is_null($newName)) {
            $params['name'] = $newName;
        }
        if (!is_null($roles)) {
            $params['roles'] = $roles;
        }

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = ['X-Audit-Log-Reason' => $reason];
        }

        $res = $apiClient->patch("guilds/${guildId}/emojis/${emojiId}", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $emojiId
     * @param string $reason
     * @return bool
     * @throws GuzzleException
     */
    public static function delete(Client $apiClient, Snowflake $guildId, Snowflake $emojiId, string $reason = ''): bool
    {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = ['X-Audit-Log-Reason' => $reason];
        }

        $res = $apiClient->delete("guilds/${guildId}/emojis/${emojiId}", $options);
        return $res->getStatusCode() === 204;
    }

    /**
     * The id of the emote
     *
     * @return ?Snowflake
     */
    public function getId(): ?Snowflake
    {
        return $this->id;
    }

    /**
     * The name of the emote
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * The roles that are whitelisted to use the emoji
     *
     * @return int[]
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * The user that created the emoji
     *
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * If the emoji needs colons
     *
     * @return bool
     */
    public function getRequireColons(): ?bool
    {
        return $this->require_colons;
    }

    /**
     * Whether this is a custom emoji
     *
     * Custom emojis have an id, while regular ones only have a name
     * @return bool
     */
    public function isCustom(): bool
    {
        return $this->getId() === null;
    }

    /**
     * If the image is managed by a bot/integration
     *
     * @return bool
     */
    public function isManaged(): ?bool
    {
        return $this->managed;
    }

    /**
     * Whether the emoji is animated
     *
     * @return bool
     */
    public function isAnimated(): ?bool
    {
        return $this->animated;
    }

    /**
     * Whether the emoji is available to use
     *
     * @return bool
     */
    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    /**
     * The image url of the emoji
     *
     * @param int $size
     * @return ?string
     */
    public function getImageUrl(int $size = 1024): ?string
    {
        $id = $this->getId();
        if (!$id) {
            return null;
        }

        // make sure the size is within the allowed range
        $size = max(min($size, 4096), 16);

        $baseUrl = Bot::BaseImageUrl;
        $format = $this->isAnimated() ? 'gif' : 'png';
        return "${baseUrl}emojis/${id}.${format}?size=${size}";
    }
}