<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class RoleTags
{
    protected ?Snowflake $botId;
    protected ?Snowflake $integrationId;
    protected bool $premiumSubscriber;

    public function __construct(array $data)
    {
        $this->botId = $data['bot_id'] ? new Snowflake($data['bot_id']) : null;
        $this->integrationId = $data['integration_id'] ? new Snowflake($data['integration_id']) : null;
        $this->premiumSubscriber = $data['premium_subscriber'] ?? false;
    }

    /**
     * Returns the bot id, if available
     *
     * @return ?Snowflake
     */
    public function getBotId(): ?Snowflake
    {
        return $this->botId;
    }

    /**
     * Returns the integration id, if available
     *
     * @return ?Snowflake
     */
    public function getIntegrationId(): ?Snowflake
    {
        return $this->integrationId;
    }

    /**
     * Returns whether this is the premium subscriber role (aka. nitro booster)
     *
     * @return bool
     */
    public function isNitroBooster(): bool
    {
        return $this->premiumSubscriber;
    }
}