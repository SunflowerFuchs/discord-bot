<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SunflowerFuchs\DiscordBot\Api\Constants\Headers;
use SunflowerFuchs\DiscordBot\Api\Constants\Permissions;
use SunflowerFuchs\DiscordBot\Bot;

class Role
{
    /**
     * role id
     */
    protected Snowflake $id;
    /**
     * role name
     */
    protected string $name;
    /**
     * integer representation of hexadecimal color code
     */
    protected int $color;
    /**
     * if this role is pinned in the user listing
     */
    protected bool $hoist;
    /**
     * role icon hash
     */
    protected ?string $icon;
    /**
     * role unicode emoji
     */
    protected ?string $unicode_emoji;
    /**
     * position of this role
     */
    protected int $position;
    /**
     * permission bit set
     */
    protected int $permissions;
    /**
     * whether this role is managed by an integration
     */
    protected bool $managed;
    /**
     * whether this role is mentionable
     */
    protected bool $mentionable;
    /**
     * the tags this role has
     */
    protected ?RoleTags $tags;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->color = $data['color'];
        $this->hoist = $data['hoist'];
        $this->icon = $data['icon'] ?? null;
        $this->unicode_emoji = $data['unicode_emoji'] ?? null;
        $this->position = $data['position'];
        $this->permissions = (int)$data['permissions'];
        $this->managed = $data['managed'];
        $this->mentionable = $data['mentionable'];
        $this->tags = new RoleTags($data['tags'] ?? []);
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param string $name
     * @param int $permissions
     * @param int $color
     * @param bool $hoist
     * @param string $icon
     * @param string $unicodeEmoji
     * @param bool $mentionable
     * @param string $reason
     * @return ?static
     * @throws GuzzleException
     */
    public static function create(
        Client $apiClient,
        Snowflake $guildId,
        string $name = 'new role',
        int $permissions = 0,
        int $color = 0,
        bool $hoist = false,
        string $icon = '',
        string $unicodeEmoji = '',
        bool $mentionable = false,
        string $reason = ''
    ): ?self {
        $params = [
            'name' => $name,
            'permissions' => $permissions,
            'color' => $color,
            'hoist' => $hoist,
            'icon' => $icon,
            'unicodeEmoji' => $unicodeEmoji,
            'mentionable' => $mentionable,
        ];

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->post("guilds/${guildId}/roles", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $roleId
     * @param ?string $name
     * @param ?int $permissions
     * @param ?int $color
     * @param ?bool $hoist
     * @param ?string $icon
     * @param ?string $unicodeEmoji
     * @param ?bool $mentionable
     * @param string $reason
     * @return ?static
     * @throws GuzzleException
     */
    public static function modify(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $roleId,
        ?string $name = null,
        ?int $permissions = null,
        ?int $color = null,
        ?bool $hoist = null,
        ?string $icon = null,
        ?string $unicodeEmoji = null,
        ?bool $mentionable = null,
        string $reason = ''
    ): ?self {
        $params = [
            'name' => $name,
            'permissions' => $permissions,
            'color' => $color,
            'hoist' => $hoist,
            'icon' => $icon,
            'unicodeEmoji' => $unicodeEmoji,
            'mentionable' => $mentionable,
        ];

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->patch("guilds/${guildId}/roles/${roleId}", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $roleId
     * @param int $position
     * @param string $reason
     * @return static[]
     * @throws GuzzleException
     */
    public static function move(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $roleId,
        int $position,
        string $reason = ''
    ): array {
        $params = [
            'id' => "$roleId",
            'position' => $position,
        ];

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->patch("guilds/${guildId}/roles", $options);
        if ($res->getStatusCode() === 200) {
            return array_map(fn(array $roleData) => new static($roleData),
                json_decode($res->getBody()->getContents(), true));
        }

        return [];
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $roleId
     * @param string $reason
     * @return bool
     * @throws GuzzleException
     */
    public static function delete(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $roleId,
        string $reason = ''
    ): bool {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->delete("guilds/${guildId}/roles/${roleId}", $options);
        return $res->getStatusCode() === 204;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @return static[]
     * @throw
     * @throws GuzzleException
     */
    public static function listForGuild(Client $apiClient, Snowflake $guildId): array
    {
        $res = $apiClient->get("guilds/${guildId}/roles");
        if ($res->getStatusCode() === 200) {
            return array_map(fn(array $roleData) => new static($roleData),
                json_decode($res->getBody()->getContents(), true));
        }

        return [];
    }

    /**
     * The id of the role
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * Returns the name of the role
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the integer representation of the role color
     *
     * @return int
     */
    public function getColor(): int
    {
        return $this->color;
    }

    /**
     * Returns the color in hex representation
     *
     * @return string
     */
    public function getHexColor(): string
    {
        return str_pad(dechex($this->getColor()), 6, '0', STR_PAD_LEFT);
    }

    /**
     * whether the role should be displayed separately in the sidebar
     *
     * @return bool
     */
    public function isHoisted(): bool
    {
        return $this->hoist;
    }

    /**
     * Returns the position in the role list
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Returns the permissions for this role
     *
     * @return int
     * @see Permissions
     */
    public function getPermissions(): int
    {
        return $this->permissions;
    }

    /**
     * Shortcut function for permission checks
     *
     * @param int $permission See {@see Permissions}
     * @return bool
     */
    public function hasPermission(int $permission): bool
    {
        return ($this->getPermissions() & $permission) == $permission;
    }

    /**
     * Whether this role is managed by an integration
     * @return bool
     */
    public function isManaged(): bool
    {
        return $this->managed;
    }

    /**
     * Whether this role can be mentioned
     * @return bool
     */
    public function isMentionable(): bool
    {
        return $this->mentionable;
    }

    /**
     * The tags this role has
     *
     * @return ?RoleTags
     */
    public function getTags(): ?RoleTags
    {
        return $this->tags;
    }

    /**
     * Whether this role belongs to the given bot
     * @param Bot $bot
     * @return bool
     */
    public function belongsToBot(Bot $bot): bool
    {
        return $this->isManaged() && $this->getTags() !== null && $this->getTags()->getBotId() === $bot->getUserId();
    }
}