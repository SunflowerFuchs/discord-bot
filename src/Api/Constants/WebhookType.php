<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class WebhookType
{
    /**
     * Incoming Webhooks can post messages to channels with a generated token
     */
    const INCOMING = 1;
    /**
     * Follower    Channel Follower Webhooks are internal webhooks used with Channel Following to post new messages into channels
     */
    const CHANNEL = 2;
    /**
     * Application webhooks are webhooks used with Interactions
     */
    const APPLICATION = 3;
}