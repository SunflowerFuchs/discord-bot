<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class ThreadMetadata
{
    /**
     * whether the thread is archived
     */
    protected bool $archived;
    /**
     * duration in minutes to automatically archive the thread after recent activity, can be set to: 60, 1440, 4320, 10080
     */
    protected int $auto_archive_duration;
    /**
     * timestamp when the thread's archive status was last changed, used for calculating recent activity
     */
    protected int $archive_timestamp;
    /**
     * whether the thread is locked; when a thread is locked, only users with MANAGE_THREADS can unarchive it
     */
    protected bool $locked;
    /**
     * whether non-moderators can add other non-moderators to a thread; only available on private threads
     */
    protected bool $invitable;

    public function __construct(array $data)
    {
        $this->archived = $data['archived'];
        $this->auto_archive_duration = $data['auto_archive_duration'];
        $this->archive_timestamp = strtotime($data['archive_timestamp']);
        $this->locked = $data['locked'];
        $this->invitable = $data['invitable'] ?? false;
    }

    /**
     * whether the thread is archived
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * duration in minutes to automatically archive the thread after recent activity, can be set to: 60, 1440, 4320, 10080
     */
    public function getAutoArchiveDuration()
    {
        return $this->auto_archive_duration;
    }

    /**
     * timestamp when the thread's archive status was last changed, used for calculating recent activity
     */
    public function getArchiveTimestamp()
    {
        return $this->archive_timestamp;
    }

    /**
     * whether the thread is locked; when a thread is locked, only users with MANAGE_THREADS can unarchive it
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * whether non-moderators can add other non-moderators to a thread; only available on private threads
     */
    public function getInvitable()
    {
        return $this->invitable;
    }
}