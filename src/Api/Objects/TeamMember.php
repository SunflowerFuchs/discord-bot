<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\MembershipState;

class TeamMember
{
    /**
     * the user's membership state on the team
     * @see MembershipState
     */
    protected int $membership_state;
    /**
     * will always be ["*"]
     * @var string[]
     */
    protected array $permissions;
    /**
     * the id of the parent team of which they are a member
     */
    protected Snowflake $team_id;
    /**
     * the avatar, discriminator, id, and username of the user
     */
    protected User $user;

    public function __construct(array $data)
    {
        $this->membership_state = $data['membership_state'];
        $this->permissions = $data['permissions'];
        $this->team_id = new Snowflake($data['team_id']);
        $this->user = new User($data['user']);
    }

    /**
     * the user's membership state on the team
     * @return int
     */
    public function getMembershipState(): int
    {
        return $this->membership_state;
    }

    /**
     * will always be ["*"]
     * @return string[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * the id of the parent team of which they are a member
     * @return Snowflake
     */
    public function getTeamId(): Snowflake
    {
        return $this->team_id;
    }

    /**
     * the avatar, discriminator, id, and username of the user
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}