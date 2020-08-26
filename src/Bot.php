<?php

namespace SunflowerFuchs\DiscordBot;

use InvalidArgumentException;
use SunflowerFuchs\DiscordBot\Plugins\PluginTrait;

class Bot
{
    protected array $options;
    protected array $plugins = [];
    protected array $commands = [];

    public function __construct(array $options)
    {
        $this->setOptions($options);


    }

    public function run()
    {
        // TODO
    }

    protected function setOptions(array $options): void
    {
        $this->options = $this->cleanupOptions($options);
    }

    protected function validateOptions(array $options): void
    {
        $required = [
            'token',
            'prefix'
        ];
        if (array_diff_key($required, $options) !== []) {
            throw new InvalidArgumentException('Required argument missing.');
        }
    }

    protected function cleanupOptions(array $options): array
    {
        $this->validateOptions($options);
        $options['prefix'] = trim($options['prefix']);
        $options['token'] = trim($options['token']);
        return $options;
    }

    public function registerPlugin(PluginTrait $plugin)
    {
        $class = get_class($plugin);
        $this->plugins[$class] = $plugin->init($this);

        foreach ($this->plugins[$class]->getCommands() as $command => $function) {
            if (isset($this->commands[$command])) {
                $oldClass = $this->commands[$command]['plugin'];
                echo "$class redefines $command, overwriting $oldClass" . PHP_EOL;
            }

            $this->commands[$command] = [
                'plugin' => $class,
                'function' => $function
            ];
        }
    }
}