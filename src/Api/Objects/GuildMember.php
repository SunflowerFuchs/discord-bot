<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use GuzzleHttp\Client;

class GuildMember
{
    /**
     * the user this guild member represents
     */
    protected ?User $user;
    /**
     * this users guild nickname
     */
    protected ?string $nick;
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
    protected ?string $permissions;

    public function __construct(array $data)
    {
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->nick = $data['nick'] ?? null;
        $this->joined_at = strtotime($data['joined_at']);
        $this->premium_since = !empty($data['premium_since']) ? strtotime($data['premium_since']) : null;
        $this->deaf = $data['deaf'] ?? false;
        $this->mute = $data['mute'] ?? false;
        $this->pending = $data['pending'] ?? false;
        $this->roles = array_map(fn(string $snowflake) => new Snowflake($snowflake), $data['roles'] ?? []);
        $this->permissions = $data['permissions'] ?? null;
    }

    public static function loadById(Client $apiClient, string $guildId, string $userId): ?self
    {
        $ref = $apiClient->get("/guilds/${guildId}/members/${userId}");
        if ($ref->getStatusCode() !== 200) {
            return null;
        }

        return new static(json_decode($ref->getBody()->getContents(), true));
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
     * @return ?string
     */
    public function getNick(): ?string
    {
        return $this->nick;
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
    public function getPermissions(): string
    {
        return $this->permissions;
    }


}