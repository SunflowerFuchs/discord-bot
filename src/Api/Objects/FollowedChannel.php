<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;

class FollowedChannel
{
    /**
     * source channel id
     */
    protected Snowflake $channel_id;
    /**
     * created target webhook id
     */
    protected Snowflake $webhook_id;

    public function __construct(array $data)
    {
        $this->channel_id = new Snowflake($data['channel_id']);
        $this->webhook_id = new Snowflake($data['webhook_id']);
    }

    /**
     * source channel id
     * @return Snowflake
     */
    public function getChannelId(): Snowflake
    {
        return $this->channel_id;
    }

    /**
     * created target webhook id
     * @return Snowflake
     */
    public function getWebhookId(): Snowflake
    {
        return $this->webhook_id;
    }

    public static function follow(Client $apiClient, string $channelId, string $targetChannelId): ?self
    {
        $options = [
            'json' => [
                'webhook_channel_id' => $targetChannelId
            ]
        ];

        $res = $apiClient->post("channels/${channelId}/followers", $options);
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }
}