<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SunflowerFuchs\DiscordBot\Api\Constants\Headers;
use SunflowerFuchs\DiscordBot\Api\Constants\IntegrationExpireBehaviour;

class Integration
{
    /**
     * integration id
     */
    protected Snowflake $id;
    /**
     * integration name
     */
    protected string $name;
    /**
     * integration type (twitch, youtube, or discord)
     */
    protected string $type;
    /**
     * is this integration enabled
     */
    protected bool $enabled;
    /**
     * is this integration syncing
     *
     * this field is not provided for discord bot integrations
     */
    protected bool $syncing;
    /**
     * id that this integration uses for "subscribers"
     *
     * this field is not provided for discord bot integrations
     */
    protected Snowflake $role_id;
    /**
     * whether emoticons should be synced for this integration (twitch only currently)
     *
     * this field is not provided for discord bot integrations
     */
    protected bool $enable_emoticons;
    /**
     * the behavior of expiring subscribers
     *
     * this field is not provided for discord bot integrations
     *
     * @see IntegrationExpireBehaviour
     */
    protected int $expire_behavior;
    /**
     * the grace period (in days) before expiring subscribers
     *
     * this field is not provided for discord bot integrations
     */
    protected int $expire_grace_period;
    /**
     * user for this integration
     *
     * this field is not provided for discord bot integrations
     */
    protected ?User $user;
    /**
     * integration account information
     */
    protected IntegrationAccount $account;
    /**
     * when this integration was last synced
     *
     * this field is not provided for discord bot integrations
     */
    protected int $synced_at;
    /**
     * how many subscribers this integration has
     *
     * this field is not provided for discord bot integrations
     */
    protected int $subscriber_count;
    /**
     * has this integration been revoked
     *
     * this field is not provided for discord bot integrations
     */
    protected bool $revoked;
    /**
     * The bot/OAuth2 application for discord integrations
     */
    protected ?Application $application;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->enabled = $data['enabled'] ?? true;
        $this->syncing = $data['syncing'] ?? true;
        $this->role_id = !empty($data['role_id']) ? new Snowflake($data['role_id']) : null;
        $this->enable_emoticons = $data['enable_emoticons'] ?? true;
        $this->expire_behavior = $data['expire_behavior'] ?? IntegrationExpireBehaviour::REMOVE_ROLE;
        $this->expire_grace_period = $data['expire_grace_period'] ?? 0;
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->account = new IntegrationAccount($data['account']);
        $this->synced_at = !empty($data['synced_at']) ? strtotime($data['synced_at']) : 0;
        $this->subscriber_count = $data['subscriber_count'] ?? 0;
        $this->revoked = $data['revoked'] ?? false;
        $this->application = !empty($data['application']) ? new Application($data['application']) : null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @return static[]
     * @throws GuzzleException
     */
    public static function loadByGuildId(
        Client $apiClient,
        Snowflake $guildId
    ): array {
        $res = $apiClient->get("guilds/${guildId}/integrations");
        if ($res->getStatusCode() === 200) {
            return array_map(fn(array $integrationData) => new static($integrationData),
                json_decode($res->getBody()->getContents(), true));
        }

        return [];
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @param Snowflake $integrationId
     * @param string $reason
     * @return bool
     * @throws GuzzleException
     */
    public static function deleteById(
        Client $apiClient,
        Snowflake $guildId,
        Snowflake $integrationId,
        string $reason = ''
    ): bool {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = [Headers::AUDIT_LOG_REASON => $reason];
        }

        $res = $apiClient->delete("guilds/${guildId}/integrations/${integrationId}", $options);
        return $res->getStatusCode() === 204;
    }

    /**
     * integration id
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * integration name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * integration type (twitch, youtube, or discord)
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * is this integration enabled
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * is this integration syncing
     * @return bool
     */
    public function isSyncing(): bool
    {
        return $this->syncing;
    }

    /**
     * id that this integration uses for "subscribers"
     * @return Snowflake
     */
    public function getRoleId(): Snowflake
    {
        return $this->role_id;
    }

    /**
     * whether emoticons should be synced for this integration (twitch only currently)
     * @return bool
     */
    public function isEnableEmoticons(): bool
    {
        return $this->enable_emoticons;
    }

    /**
     * the behavior of expiring subscribers
     * @return int
     */
    public function getExpireBehavior(): int
    {
        return $this->expire_behavior;
    }

    /**
     * the grace period (in days) before expiring subscribers
     * @return int
     */
    public function getExpireGracePeriod(): int
    {
        return $this->expire_grace_period;
    }

    /**
     * user for this integration
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * integration account information
     * @return IntegrationAccount
     */
    public function getAccount(): IntegrationAccount
    {
        return $this->account;
    }

    /**
     * when this integration was last synced
     * @return int
     */
    public function getSyncedAt(): int
    {
        return $this->synced_at;
    }

    /**
     * how many subscribers this integration has
     * @return int
     */
    public function getSubscriberCount(): int
    {
        return $this->subscriber_count;
    }

    /**
     * has this integration been revoked
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    /**
     * The bot/OAuth2 application for discord integrations
     * @return ?Application
     */
    public function getApplication(): ?Application
    {
        return $this->application;
    }
}