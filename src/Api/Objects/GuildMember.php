<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use GuzzleHttp\Client;
use SunflowerFuchs\DiscordBot\Api\Constants\Headers;

class GuildMember
{
    /**
     * the user this guild member represents
     */
    protected ?User $user;
    /**
     * this users guild nickname
     */
    protected string $nick;
    /**
     * the member's guild avatar hash
     */
    protected ?string $avatar;
    /**
     * array of role object ids
     * @var Snowflake[]
     */
    protected array $roles;
    /**
     * when the user joined the guild
     */
    protected int $joined_at;
    /**
     * when the user started boosting the guild
     */
    protected ?int $premium_since;
    /**
     * whether the user is deafened in voice channels
     */
    protected bool $deaf;
    /**
     * whether the user is muted in voice channels
     */
    protected bool $mute;
    /**
     * whether the user has not yet passed the guild's Membership Screening requirements
     */
    protected bool $pending;
    /**
     * total permissions of the member in the channel, including overwrites, returned when in the interaction object
     */
    protected int $permissions;
    /**
     * when the user's timeout will expire and the user will be able to communicate in the guild again, zero if the user is not timed out
     */
    protected int $communication_disabled_until;

    public function __construct(array $data)
    {
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->nick = $data['nick'] ?? '';
        $this->avatar = $data['avatar'] ?? null;
        $this->joined_at = strtotime($data['joined_at']);
        $this->premium_since = !empty($data['premium_since']) ? strtotime($data['premium_since']) : null;
        $this->deaf = $data['deaf'] ?? false;
        $this->mute = $data['mute'] ?? false;
        $this->pending = $data['pending'] ?? false;
        $this->roles = array_map(fn(string $snowflake) => new Snowflake($snowflake), $data['roles'] ?? []);
        $this->permissions = $data['permissions'] ?? 0;
        $this->communication_disabled_until = !empty($data['communication_disabled_until']) ? strtotime($data['communication_disabled_until']) : 0;

        if (!$this->communication_disabled_until < time()) {
            $this->communication_disabled_until = 0;
        }
    }

    public static function loadById(Client $apiClient, Snowflake $guildId, Snowflake $userId): ?self
    {
        $ref = $apiClient->get("/guilds/${guildId}/members/${userId}");
        if ($ref->getStatusCode() === 200) {
            return new static(json_decode($ref->getBody()->getContents(), true));
        }

        return null;
    }

    public static function listForGuild(
        Client $apiClient,
        Snowflake $guildId,
        int $limit = 1,
        ?Snowflake $after = null
    ): array {
        $params = [
            'limit' => $limit
        ];
        if (!empty($after)) {
            $params['after'] = "$after";
        }
        $options = ['json' => $params];

        $ref = $apiClient->get("/guilds/${guildId}/members", $options);
        if ($ref->getStatusCode() === 200) {
            return array_map(fn(array $memberData) => new static($memberData),
                json_decode($ref->getBody()->getContents(), true));
        }

        return [];
    }

    public static function search(
        Client $apiClient,
        Snowflake $guildId,
        string $query,
        int $limit = 1
    ): array {
        $params = [
            'query' => $query,
            'limit' => $limit
        ];
        $options = ['json' => $params];

        $ref = $apiClient->get("/guilds/${guildId}/members/search", $options);
        if ($ref->getStatusCode() === 200) {
            return array_map(fn(array $memberData) => new static($memberData),
                json_decode($ref->getBody()->getContents(), true));
        }

        return [];
    }

    public static function kick(Client $apiClient, Snowflake $guildId, Snowflake $userId, string $reason = ''): bool
    {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->delete("guilds/${guildId}/members/${userId}", $options);
        return $res->getStatusCode() === 204;
    }

    public static function addRole(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $userId,
        Snowflake $roleId,
        string $reason = ''
    ): bool {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->put("guilds/${guildId}/members/${userId}/roles/${roleId}", $options);
        return $res->getStatusCode() === 204;
    }

    public static function removeRole(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $userId,
        Snowflake $roleId,
        string $reason = ''
    ): bool {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->delete("guilds/${guildId}/members/${userId}/roles/${roleId}", $options);
        return $res->getStatusCode() === 204;
    }

    /**
     * Returns the user object of the member
     *
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Returns the users nickname for the server
     *
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * Returns the users avatar hash for the server
     *
     * @return ?string
     */
    public function getAvatarHash(): ?string
    {
        return $this->avatar;
    }

    /**
     * An array of the roles the member has
     *
     * @return Snowflake[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * When the member joined
     *
     * @return int
     */
    public function getJoinedAt(): int
    {
        return $this->joined_at;
    }

    /**
     * How long the member has been boosting the server
     *
     * @return ?int
     */
    public function getPremiumSince(): ?int
    {
        return $this->premium_since;
    }

    /**
     * Whether the user is deafened
     *
     * @return bool
     */
    public function isDeafened(): bool
    {
        return $this->deaf;
    }

    /**
     * Whether the user is muted
     *
     * @return bool
     */
    public function isMuted(): bool
    {
        return $this->mute;
    }

    /**
     * Whether the user passed the guild screening
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->pending;
    }

    /**
     * total permissions of the member in the channel, including overwrites, returned when in the interaction object
     */
    public function getPermissions(): int
    {
        return $this->permissions;
    }

    /**
     * when the user's timeout will expire and the user will be able to communicate in the guild again, zero if the user is not timed out
     *
     * @return int
     */
    public function getCommunicationDisabledUntil(): int
    {
        return $this->communication_disabled_until;
    }

    /**
     * whether the user is currently timed out
     *
     * @return bool
     */
    public function isTimedOut(): bool
    {
        return $this->getCommunicationDisabledUntil() !== 0;
    }
}