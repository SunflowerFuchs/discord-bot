<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Webhook
{
    /**
     * the id of the webhook
     */
    protected Snowflake $id;
    /**
     * the type of the webhook
     * @see WebhookType
     */
    protected int $type;
    /**
     * the guild id this webhook is for, if any
     */
    protected ?Snowflake $guild_id;
    /**
     * the channel id this webhook is for, if any
     */
    protected ?Snowflake $channel_id;
    /**
     * the user this webhook was created by (not returned when getting a webhook with its token)
     */
    protected ?User $user;
    /**
     * the default name of the webhook
     */
    protected string $name;
    /**
     * the default user avatar hash of the webhook
     */
    protected string $avatar;
    /**
     * the secure token of the webhook (returned for Incoming Webhooks)
     */
    protected string $token;
    /**
     * the bot/OAuth2 application that created this webhook
     */
    protected Snowflake $application_id;
    /**
     * the guild of the channel that this webhook is following (returned for Channel Follower Webhooks)
     */
    protected ?Guild $source_guild;
    /**
     * the channel that this webhook is following (returned for Channel Follower Webhooks)
     */
    protected ?Channel $source_channel;
    /**
     * the url used for executing the webhook (returned by the webhooks OAuth2 flow)
     */
    protected string $url;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->type = $data['type'];
        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->channel_id = !empty($data['channel_id']) ? new Snowflake($data['channel_id']) : null;
        $this->user = !empty($data['user']) ? new User($data['user']) : null;
        $this->name = $data['name'] ?? '';
        $this->avatar = $data['avatar'] ?? '';
        $this->token = $data['token'] ?? '';
        $this->application_id = !empty($data['application_id']) ? new Snowflake($data['application_id']) : null;
        $this->source_guild = !empty($data['source_guild']) ? new Guild($data['source_guild']) : null;
        $this->source_channel = !empty($data['source_channel']) ? new Channel($data['source_channel']) : null;
        $this->url = $data['url'] ?? '';
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $channelId
     * @param string $name
     * @param string $avatar
     * @param string $reason
     * @return static|null
     * @throws GuzzleException
     */
    public static function create(
        Client $apiClient,
        Snowflake $channelId,
        string $name,
        string $avatar = '',
        string $reason = ''
    ): ?self {
        $params = ['name' => $name];
        if (!empty($avatar)) {
            $params['avatar'] = $avatar;
        }

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = ['X-Audit-Log-Reason' => $reason];
        }

        $res = $apiClient->post("channels/${channelId}/webhooks", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $webhookId
     * @param string $name
     * @param string $avatar
     * @param ?Snowflake $channelId
     * @param string $reason
     * @return ?static
     * @throws GuzzleException
     */
    public static function modify(
        Client $apiClient,
        Snowflake $webhookId,
        string $name = '',
        string $avatar = '',
        ?Snowflake $channelId = null,
        string $reason = ''
    ): ?self {
        $params = [];
        if (!empty($name)) {
            $params['name'] = $name;
        }
        if (!empty($avatar)) {
            $params['avatar'] = $avatar;
        }
        if (!empty($channelId)) {
            $params['channel_id'] = "${channelId}";
        }

        $options = [
            'json' => $params
        ];
        if (!empty($reason)) {
            $options['headers'] = ['X-Audit-Log-Reason' => $reason];
        }

        $res = $apiClient->patch("webhooks/${webhookId}", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    public static function delete(Client $apiClient, Snowflake $webhookId, string $reason = ''): bool
    {
        $options = [];
        if (!empty($reason)) {
            $options['headers'] = ['X-Audit-Log-Reason' => $reason];
        }

        $res = $apiClient->delete("webhooks/${webhookId}", $options);
        return $res->getStatusCode() === 204;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $channelId
     * @return static[]
     * @throws GuzzleException
     */
    public static function listForChannel(Client $apiClient, Snowflake $channelId): array
    {
        $res = $apiClient->get("channels/${channelId}/webhooks");
        if ($res->getStatusCode() === 200) {
            return array_map(fn(array $emojiData) => new static($emojiData),
                json_decode($res->getBody()->getContents(), true));
        }

        return [];
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $guildId
     * @return static[]
     * @throws GuzzleException
     */
    public static function listForGuild(Client $apiClient, Snowflake $guildId): array
    {
        $res = $apiClient->get("guilds/${guildId}/webhooks");
        if ($res->getStatusCode() === 200) {
            return array_map(fn(array $emojiData) => new static($emojiData),
                json_decode($res->getBody()->getContents(), true));
        }

        return [];
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $webhookId
     * @return static
     * @throws GuzzleException
     */
    public static function loadById(Client $apiClient, Snowflake $webhookId): ?self
    {
        $res = $apiClient->get("webhooks/${webhookId}");
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * the id of the webhook
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * the type of the webhook
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * the guild id this webhook is for, if any
     * @return ?Snowflake
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * the channel id this webhook is for, if any
     * @return ?Snowflake
     */
    public function getChannelId(): ?Snowflake
    {
        return $this->channel_id;
    }

    /**
     * the user this webhook was created by (not returned when getting a webhook with its token)
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * the default name of the webhook
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * the default user avatar hash of the webhook
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * the secure token of the webhook (returned for Incoming Webhooks)
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * the bot/OAuth2 application that created this webhook
     * @return Snowflake
     */
    public function getApplicationId(): Snowflake
    {
        return $this->application_id;
    }

    /**
     * the guild of the channel that this webhook is following (returned for Channel Follower Webhooks)
     * @return ?Guild
     */
    public function getSourceGuild(): ?Guild
    {
        return $this->source_guild;
    }

    /**
     * the channel that this webhook is following (returned for Channel Follower Webhooks)
     * @return ?Channel
     */
    public function getSourceChannel(): ?Channel
    {
        return $this->source_channel;
    }

    /**
     * the url used for executing the webhook (returned by the webhooks OAuth2 flow)
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}