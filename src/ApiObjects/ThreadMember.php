<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class ThreadMember
{
    /**
     * the id of the thread
     */
    protected ?snowflake $id;
    /**
     * the id of the user
     */
    protected ?snowflake $user_id;
    /**
     * the time the current user last joined the thread
     */
    protected int $join_timestamp;
    /**
     * any user-thread settings, currently only used for notifications
     */
    protected int $flags;

    public function __construct(array $data)
    {
        $this->id = !empty($data['id']) ? new Snowflake($data['id']) : null;
        $this->user_id = !empty($data['user_id']) ? new Snowflake($data['user_id']) : null;
        $this->join_timestamp = strtotime($data['join_timestamp']);
        $this->join_timestamp = $data['flags'];
    }

    /**
     * the id of the thread
     */
    public function getId(): ?Snowflake
    {
        return $this->id;
    }

    /**
     * the id of the user
     */
    public function getUserId(): ?Snowflake
    {
        return $this->user_id;
    }

    /**
     * the time the current user last joined the thread
     */
    public function getJoinTimestamp()
    {
        return $this->join_timestamp;
    }

    /**
     * any user-thread settings, currently only used for notifications
     */
    public function getFlags(): int
    {
        return $this->flags;
    }
}