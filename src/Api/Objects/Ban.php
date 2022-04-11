<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;
use SunflowerFuchs\DiscordBot\Api\Constants\Headers;

class Ban
{
    /**
     * the reason for the ban
     */
    protected string $reason;
    /**
     * the banned user
     */
    protected User $user;

    public function __construct(array $data)
    {
        $this->reason = $data['reason'] ?? '';
        $this->user = new User($data['user']);
    }

    public static function list(
        Client $apiClient,
        Snowflake $guildId,
        int $limit = 50,
        ?Snowflake $before = null,
        ?Snowflake $after = null
    ): array {
        $params = ['limit' => $limit];
        if (!empty($before)) {
            $params['before'] = "$before";
        }
        if (!empty($after)) {
            $params['after'] = "$after";
        }

        $ref = $apiClient->get("/guilds/${guildId}/bans?" . http_build_query($params));
        if ($ref->getStatusCode() === 200) {
            return array_map(fn(array $banData) => new static($banData),
                json_decode($ref->getBody()->getContents(), true));
        }

        return [];
    }

    public static function loadById(Client $apiClient, Snowflake $guildId, Snowflake $userId): ?self
    {
        $ref = $apiClient->get("/guilds/${guildId}/bans/${userId}");
        if ($ref->getStatusCode() === 200) {
            return new static(json_decode($ref->getBody()->getContents(), true));
        }

        return null;
    }

    public static function create(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $userId,
        int $deleteDays = 0,
        string $reason = ''
    ): bool {
        $params = [
            'delete_message_days' => $deleteDays
        ];

        $options = [
            'json' => $params,
        ];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->put("guilds/${guildId}/bans/${userId}", $options);
        return $res->getStatusCode() === 204;
    }

    public static function delete(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $userId,
        string $reason = ''
    ): bool {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->delete("guilds/${guildId}/bans/${userId}", $options);
        return $res->getStatusCode() === 204;
    }

    /**
     * the reason for the ban
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * the banned user
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}