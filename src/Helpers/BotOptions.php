<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BotOptions extends OptionsResolver
{
    const DEFAULT_PREFIX = '!';
    const DEFAULT_LOGLEVEL = 'warning';
    const LOGLEVELS = [
        'debug' => LOG_DEBUG,
        'info' => LOG_INFO,
        'warning' => LOG_WARNING,
        'error' => LOG_ERR,
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
            ->allowedValues(...array_keys(static::LOGLEVELS))
            ->normalize(function (Options $options, string $value) {
                return static::LOGLEVELS[$value];
            });
    }
}