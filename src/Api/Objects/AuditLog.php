<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SunflowerFuchs\DiscordBot\Api\Constants\AuditLogEventType;

class AuditLog
{
    /**
     * list of audit log entries
     * @var AuditLogEntry[]
     */
    protected array $audit_log_entries;
    /**
     * list of guild scheduled events found in the audit log
     * @var GuildScheduledEvent[]
     */
    protected array $guild_scheduled_events;
    /**
     * list of partial integration objects
     * @var Integration[]
     */
    protected array $integrations;
    /**
     * list of threads found in the audit log
     *
     * Threads referenced in THREAD_CREATE and THREAD_UPDATE events are included in the threads map, since archived threads might not be kept in memory by clients.
     *
     * @var Channel[]
     */
    protected array $threads;
    /**
     * list of users found in the audit log
     * @var User[]
     */
    protected array $users;
    /**
     * list of webhooks found in the audit log
     * @var Webhook[]
     */
    protected array $webhooks;

    public function __construct(array $data)
    {
        $this->audit_log_entries = array_map(fn(array $entryData) => new aa($entryData),
            $data['audit_log_entries']);
        $this->guild_scheduled_events = array_map(fn(array $eventData) => new GuildScheduledEvent($eventData),
            $data['guild_scheduled_events']);
        $this->integrations = array_map(fn(array $integrationData) => new Integration($integrationData),
            $data['integrations']);
        $this->threads = array_map(fn(array $channelData) => new Channel($channelData),
            $data['threads']);
        $this->users = array_map(fn(array $userData) => new User($userData),
            $data['users']);
        $this->webhooks = array_map(fn(array $webhookData) => new Webhook($webhookData),
            $data['webhooks']);
    }

    /**
     * @param Client $apiClient
     * @param string $guildId
     * @param ?Snowflake $userId filter the log for actions made by a specific user
     * @param ?int $actionType the type of {@see AuditLogEventType}
     * @param ?Snowflake $before filter the log before a certain entry id
     * @param int $limit how many entries are returned (default 50, minimum 1, maximum 100)
     * @return ?static
     * @throws GuzzleException
     */
    public static function loadByGuildId(
        Client $apiClient,
        string $guildId,
        ?Snowflake $userId = null,
        ?int $actionType = null,
        ?Snowflake $before = null,
        int $limit = 50
    ): ?self {
        $params = [
            'limit' => $limit
        ];
        if (!is_null($userId)) {
            $params['user_id'] = (string)$userId;
        }
        if (!is_null($actionType)) {
            $params['action_type'] = $actionType;
        }
        if (!is_null($before)) {
            $params['before'] = (string)$before;
        }

        $res = $apiClient->get("guilds/${guildId}/audit-logs?" . http_build_query($params));
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * list of audit log entries
     * @return AuditLogEntry[]
     */
    public function getAuditLogEntries(): array
    {
        return $this->audit_log_entries;
    }

    /**
     * list of guild scheduled events found in the audit log
     * @return GuildScheduledEvent[]
     */
    public function getGuildScheduledEvents(): array
    {
        return $this->guild_scheduled_events;
    }

    /**
     * list of partial integration objects
     * @return Integration[]
     */
    public function getIntegrations(): array
    {
        return $this->integrations;
    }

    /**
     * list of threads found in the audit log
     * @return Channel[]
     */
    public function getThreads(): array
    {
        return $this->threads;
    }

    /**
     * list of users found in the audit log
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * list of webhooks found in the audit log
     * @return Webhook[]
     */
    public function getWebhooks(): array
    {
        return $this->webhooks;
    }
}