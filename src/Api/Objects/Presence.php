<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\Status;

class Presence
{
    /**
     * the user presence is being updated for
     */
    protected User $user;
    /**
     * id of the guild
     */
    protected Snowflake $guild_id;
    /**
     * one of {@see Status}
     */
    protected string $status;
    /**
     * user's current activities
     * @var UserActivity[]
     */
    protected array $activities;
    /**
     * user's platform-dependent status
     */
    protected ClientStatus $client_status;

    public function __construct(array $data)
    {
        $this->user = new User($data['user']);
        $this->guild_id = new Snowflake($data['guild_id']);
        $this->status = $data['status'];
        $this->client_status = new ClientStatus($data['client_status']);

        $this->activities = array_map(fn(array $activityData) => new UserActivity($activityData),
            $data['activities']);
    }

    /**
     * the user presence is being updated for
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * id of the guild
     * @return Snowflake
     */
    public function getGuildId(): Snowflake
    {
        return $this->guild_id;
    }

    /**
     * the users current status
     *
     * one of {@see Status}
     * @return mixed|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * user's current activities
     * @return UserActivity[]
     */
    public function getActivities(): array
    {
        return $this->activities;
    }

    /**
     * user's platform-dependent status
     * @return ClientStatus
     */
    public function getClientStatus(): ClientStatus
    {
        return $this->client_status;
    }
}