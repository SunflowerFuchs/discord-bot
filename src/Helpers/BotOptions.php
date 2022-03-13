<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use Psr\Log\LogLevel;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BotOptions extends OptionsResolver
{
    protected const DEFAULT_PREFIX = '!';
    protected const DEFAULT_LOGLEVEL = LogLevel::INFO;
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

        $this->define('dataDir')
            ->default(getcwd() . DIRECTORY_SEPARATOR . 'data/')
            ->allowedTypes('string')
            ->normalize(function (Options $options, string $value): string {
                // Ensure trailing slash
                if (substr($value, -strlen(DIRECTORY_SEPARATOR)) !== DIRECTORY_SEPARATOR) {
                    $value .= DIRECTORY_SEPARATOR;
                }

                // if dir already exists, we can successfully return here
                if (is_dir($value)) {
                    return $value;
                }

                // try to create the directory and return it on success
                if (@mkdir($value, 0775, true) === true) {
                    return $value;
                }

                throw new InvalidOptionsException('The option "dataDir" was expected to be a valid directory, but it could neither be found nor created. Try using an absolute path, or check its permissions.');
            });
    }
}