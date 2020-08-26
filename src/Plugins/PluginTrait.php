<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use SunflowerFuchs\DiscordBot\Bot;

trait PluginTrait
{
    protected Bot $bot;

    protected array $commands = [];

    public function init(Bot $bot): self
    {
        $this->validateFunctions();
        $this->bot = $bot;
        return $this;
    }

    protected function sendMessage(string $message): bool
    {
        return $this->bot->sendMessage($message);
    }

    public function getCommands()
    {
        return $this->commands;
    }

    protected function validateFunctions()
    {
        foreach ($this->commands as $function) {
            if (!is_callable($this->$function)) {
                throw new \InvalidArgumentException("Undefined function '${function}' in class " . static::class);
            }
        }
    }
}