<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\Permissions;

class Role
{
    protected Snowflake $id;
    protected string $name;
    protected int $color;
    protected bool $hoist;
    protected int $position;
    protected int $permissions;
    protected bool $managed;
    protected bool $mentionable;
    protected RoleTags $tags;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->color = $data['color'];
        $this->hoist = $data['hoist'];
        $this->position = $data['position'];
        $this->permissions = (int)$data['permissions'];
        $this->managed = $data['managed'];
        $this->mentionable = $data['mentionable'];
        $this->tags = new RoleTags($data['tags'] ?? []);
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
     * Whether the role is pinned in the user listing
     *
     * @return bool
     */
    public function isHoist(): bool
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
     * @return string
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
     * @return RoleTags
     */
    public function getTags(): RoleTags
    {
        return $this->tags;
    }
}