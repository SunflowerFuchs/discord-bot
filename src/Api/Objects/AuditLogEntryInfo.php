<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class AuditLogEntryInfo
{
    /**
     * channel in which the entities were targeted
     *
     * MEMBER_MOVE & MESSAGE_PIN & MESSAGE_UNPIN & MESSAGE_DELETE & STAGE_INSTANCE_CREATE & STAGE_INSTANCE_UPDATE & STAGE_INSTANCE_DELETE
     */
    protected ?Snowflake $channel_id;
    /**
     * number of entities that were targeted
     *
     * MESSAGE_DELETE & MESSAGE_BULK_DELETE & MEMBER_DISCONNECT & MEMBER_MOVE
     */
    protected ?string $count;
    /**
     * number of days after which inactive members were kicked
     *
     * MEMBER_PRUNE
     */
    protected ?string $delete_member_days;
    /**
     * id of the overwritten entity
     *
     * CHANNEL_OVERWRITE_CREATE & CHANNEL_OVERWRITE_UPDATE & CHANNEL_OVERWRITE_DELETE
     */
    protected ?Snowflake $id;
    /**
     * number of members removed by the prune
     *
     * MEMBER_PRUNE
     */
    protected ?string $members_removed;
    /**
     * id of the message that was targeted
     *
     * MESSAGE_PIN & MESSAGE_UNPIN
     */
    protected ?Snowflake $message_id;
    /**
     * name of the role if type is "0" (not present if type is "1")
     *
     * CHANNEL_OVERWRITE_CREATE & CHANNEL_OVERWRITE_UPDATE & CHANNEL_OVERWRITE_DELETE
     */
    protected ?string $role_name;
    /**
     * type of overwritten entity - "0" for "role" or "1" for "member"
     *
     * CHANNEL_OVERWRITE_CREATE & CHANNEL_OVERWRITE_UPDATE & CHANNEL_OVERWRITE_DELETE
     */
    protected ?string $type;

    public function __construct(array $data)
    {
        $this->channel_id = !empty($data['channel_id']) ? new Snowflake($data['channel_id']) : null;
        $this->count = $data['count'] ?? null;
        $this->delete_member_days = $data['delete_member_days'] ?? null;
        $this->id = !empty($data['id']) ? new Snowflake($data['id']) : null;
        $this->members_removed = $data['members_removed'] ?? null;
        $this->message_id = !empty($data['message_id']) ? new Snowflake($data['message_id']) : null;
        $this->role_name = $data['role_name'] ?? null;
        $this->type = $data['type'] ?? null;
    }

    /**
     * channel in which the entities were targeted
     * @return ?Snowflake
     */
    public function getChannelId(): ?Snowflake
    {
        return $this->channel_id;
    }

    /**
     * number of entities that were targeted
     * @return ?string
     */
    public function getCount(): ?string
    {
        return $this->count;
    }

    /**
     * number of days after which inactive members were kicked
     * @return ?string
     */
    public function getDeleteMemberDays(): ?string
    {
        return $this->delete_member_days;
    }

    /**
     * id of the overwritten entity
     * @return ?Snowflake
     */
    public function getId(): ?Snowflake
    {
        return $this->id;
    }

    /**
     * number of members removed by the prune
     * @return ?string
     */
    public function getMembersRemoved(): ?string
    {
        return $this->members_removed;
    }

    /**
     * id of the message that was targeted
     * @return ?Snowflake
     */
    public function getMessageId(): ?Snowflake
    {
        return $this->message_id;
    }

    /**
     * name of the role if type is "0" (not present if type is "1")
     * @return ?string
     */
    public function getRoleName(): ?string
    {
        return $this->role_name;
    }

    /**
     * type of overwritten entity - "0" for "role" or "1" for "member"
     * @return ?string
     */
    public function getType(): ?string
    {
        return $this->type;
    }


}