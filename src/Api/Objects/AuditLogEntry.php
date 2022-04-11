<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class AuditLogEntry
{
    /**
     * id of the affected entity (webhook, user, role, etc.)
     */
    protected ?string $target_id;
    /**
     * changes made to the target_id
     * @var AuditLogChange[]
     */
    protected array $changes;
    /**
     * the user who made the changes
     */
    protected ?Snowflake $user_id;
    /**
     * id of the entry
     */
    protected Snowflake $id;
    /**
     * type of action that occurred
     * @see AuditLogEventType
     */
    protected int $action_type;
    /**
     * additional info for certain action types
     */
    protected ?AuditLogEntryInfo $options;
    /**
     * the reason for the change (0-512 characters)
     */
    protected string $reason;

    public function __construct(array $data)
    {
        $this->target_id = $data['target_id'] ?? null;
        $this->user_id = !empty($data['user_id']) ? new Snowflake($data['user_id']) : null;
        $this->id = new Snowflake($data['id']);
        $this->action_type = $data['action_type'];
        $this->options = !empty($data['options']) ? new AuditLogEntryInfo($data['options']) : null;
        $this->reason = $data['reason'] ?? '';

        $this->changes = array_map(fn(array $changeData) => new AuditLogChange($changeData),
            $data['changes'] ?? []);
    }

    /**
     * id of the affected entity (webhook, user, role, etc.)
     * @return ?string
     */
    public function getTargetId(): ?string
    {
        return $this->target_id;
    }

    /**
     * changes made to the target_id
     * @return AuditLogChange[]
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    /**
     * the user who made the changes
     * @return ?Snowflake
     */
    public function getUserId(): ?Snowflake
    {
        return $this->user_id;
    }

    /**
     * id of the entry
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * type of action that occurred
     * @return int
     */
    public function getActionType(): int
    {
        return $this->action_type;
    }

    /**
     * additional info for certain action types
     * @return ?AuditLogEntryInfo
     */
    public function getOptions(): ?AuditLogEntryInfo
    {
        return $this->options;
    }

    /**
     * the reason for the change (0-512 characters)
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}