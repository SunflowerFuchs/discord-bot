<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use Psr\Log\LogLevel;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BotOptions extends OptionsResolver
{
    protected const DEFAULT_PREFIX = '!';
    protected const DEFAULT_LOGLEVEL = LogLevel::WARNING;
    protected const ALLOWED_LOGLEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    public function __construct()
    {
        $this->define('token')
            ->required()
            ->allowedTypes('string');

        $this->define('prefix')
            ->default(static::DEFAULT_PREFIX)
            ->allowedTypes('string');

        $this->define('defaultPlugins')
            ->default(true)
            ->allowedTypes('bool');

        $this->define('loglevel')
            ->default(static::DEFAULT_LOGLEVEL)
            ->allowedTypes('string')
            ->allowedValues(...static::ALLOWED_LOGLEVELS);
    }
}