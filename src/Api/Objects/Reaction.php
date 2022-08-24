<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Reaction
{
    /**
     * times this emoji has been used to react
     */
    protected int $count;
    /**
     * whether the current user reacted using this emoji
     */
    protected bool $me;
    /**
     * emoji information
     */
    protected Emoji $emoji;

    public function __construct(array $data)
    {
        $this->count = $data['count'];
        $this->me = $data['me'];
        $this->emoji = new Emoji($data['emoji']);
    }

    public static function create(Client $apiClient, Snowflake $channelId, Snowflake $messageId, string $emoji)
    {
        $emoji = urlencode($emoji);
        $res = $apiClient->put("channels/${channelId}/messages/${messageId}/reactions/${emoji}/@me");
        return $res->getStatusCode() === 204;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $channelId
     * @param Snowflake $messageId
     * @param string $emoji
     * @param int $limit
     * @param Snowflake|null $after
     * @return User[]|null
     * @throws GuzzleException
     */
    public static function getUsers(
        Client $apiClient,
        Snowflake $channelId,
        Snowflake $messageId,
        string $emoji,
        int $limit = 25,
        Snowflake $after = null
    ): ?array {
        $params = ['limit' => $limit];
        if ($after !== null) {
            $params['after'] = (string)$after;
        }

        $ref = $apiClient->get("channels/${channelId}/messages/${messageId}/reactions/${emoji}?" . http_build_query($params));
        if ($ref->getStatusCode() === 200) {
            return array_map(fn(array $userData) => new User($userData),
                json_decode($ref->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $channelId
     * @param Snowflake $messageId
     * @param string $emoji
     * @return User[]|null
     * @throws GuzzleException
     */
    public static function getAllUsers(
        Client $apiClient,
        Snowflake $channelId,
        Snowflake $messageId,
        string $emoji,
    ): ?array {
        $allUsers = [];
        do {
            $lastUser = array_key_last($allUsers);
            $users = self::getUsers($apiClient, $channelId, $messageId, $emoji, 100, $lastUser);
            if ($users === null) {
                return null;
            }

            foreach ($users as $user) {
                $allUsers[$user->getId()->toInt()] = $user;
            }
        } while (is_array($users) && count($users) === 1000);

        return $allUsers;
    }

    /**
     * The amount of reactions
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Whether the current user has reacted to this
     * @return bool
     */
    public function isMyReaction(): bool
    {
        return $this->me;
    }

    /**
     * partial emoji data
     * @return Emoji
     */
    public function getEmoji(): Emoji
    {
        return $this->emoji;
    }
}