<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class UserActivitySecrets
{
    /**
     * the secret for joining a party
     */
    protected string $join;
    /**
     * the secret for spectating a game
     */
    protected string $spectate;
    /**
     * the secret for a specific instanced match
     */
    protected string $match;

    public function __construct(array $data)
    {
        $this->join = $data['join'] ?? '';
        $this->spectate = $data['spectate'] ?? '';
        $this->match = $data['match'] ?? '';
    }

    /**
     * the secret for joining a party
     * @return string
     */
    public function getJoin(): string
    {
        return $this->join;
    }

    /**
     * the secret for spectating a game
     * @return string
     */
    public function getSpectate(): string
    {
        return $this->spectate;
    }

    /**
     * the secret for a specific instanced match
     * @return string
     */
    public function getMatch(): string
    {
        return $this->match;
    }


}