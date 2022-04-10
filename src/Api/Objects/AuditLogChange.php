<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class AuditLogChange
{
    /**
     * new value of the key
     *
     * If new_value is not present in the change object, while old_value is,
     * that means the property that was changed has been reset, or set to null
     * @var mixed
     */
    protected $new_value;
    /**
     * old value of the key
     * @var mixed
     */
    protected $old_value;
    /**
     * name of audit log change key
     * @see https://discord.com/developers/docs/resources/audit-log#audit-log-change-object-audit-log-change-key
     */
    protected string $key;

    public function __construct(array $data)
    {
        $this->new_value = $data['new_value'] ?? null;
        $this->old_value = $data['old_value'] ?? null;
        $this->key = $data['key'];
    }

    /**
     * new value of the key
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->new_value;
    }

    /**
     * old value of the key
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->old_value;
    }

    /**
     * name of audit log change key
     * @see https://discord.com/developers/docs/resources/audit-log#audit-log-change-object-audit-log-change-key
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}