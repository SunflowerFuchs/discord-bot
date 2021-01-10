<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class Overwrite
{
    const TYPE_ROLE = 0;
    const TYPE_MEMBER = 1;

    protected Snowflake $id;
    protected int $type;
    protected string $allow;
    protected string $deny;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->type = intval($data['type']);
        $this->allow = $data['allow'];
        $this->deny = $data['deny'];
    }

    /**
     * Returns the role id or user id (depending on the type)
     *
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * Returns the type of the overwrite
     *
     * @return int
     * @see Overwrite::TYPE_MEMBER
     * @see Overwrite::TYPE_ROLE
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Shortcut function to check the overwrite type
     *
     * @return bool
     */
    public function isMember(): bool
    {
        return $this->getType() === static::TYPE_MEMBER;
    }

    /**
     * Shortcut function to check the overwrite type
     *
     * @return bool
     */
    public function isRole(): bool
    {
        return $this->getType() === static::TYPE_ROLE;
    }

    /**
     * Returns the permission string for additional allowed permissions
     *
     * @return string
     * @see Permissions
     */
    public function getAllow(): string
    {
        return $this->allow;
    }

    /**
     * Returns the permission string for additional denied permissions
     *
     * @return string
     * @see Permissions
     */
    public function getDeny(): string
    {
        return $this->deny;
    }
}