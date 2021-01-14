<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use InvalidArgumentException;
use SunflowerFuchs\DiscordBot\Bot;

abstract class BasePlugin
{
    /**
     * An array containing all commands and their corresponding function calls
     *
     * Add entries in this array to register the commands this plugin adds
     * The format is
     * ['command' => 'function name']
     *
     * @var array $commands
     */
    protected array $commands = [];

    public function init(): self
    {
        $this->validateFunctions();
        return $this;
    }

    protected function sendMessage(string $message, string $channelId): bool
    {
        return Bot::getInstance()->sendMessage($message, $channelId);
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    protected function validateFunctions()
    {
        foreach ($this->commands as $function) {
            if (!is_callable([$this, $function])) {
                throw new InvalidArgumentException("Undefined function '${function}' in class " . static::class);
            }
        }
    }
}