<?php

namespace SunflowerFuchs\DiscordBot;

use InvalidArgumentException;
use SunflowerFuchs\DiscordBot\Plugins\BasePlugin;
use SunflowerFuchs\DiscordBot\Plugins\PingPlugin;

class Bot
{
    protected const defaultPlugins = [
        PingPlugin::class,
    ];

    protected array $options;
    protected array $plugins = [];
    protected array $commands = [];

    public function __construct(array $options)
    {
        $this->setOptions($options);

        if ($this->options['defaultPlugins']) {
            foreach (static::defaultPlugins as $class) {
                $this->registerPlugin(new $class());
            }
        }
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

        $missing = array_diff($required, array_keys($options));
        if (!empty($missing)) {
            $missing = implode(', ', $missing);
            throw new InvalidArgumentException("Missing required argument(s): ${missing}");
        }

        // TODO: Add type validation
        //       I should probably do a whole rewrite of this function
    }

    protected function cleanupOptions(array $options): array
    {
        // TODO: In case i rewrite validateOptions, this either becomes obsolete, or should also be rewritten
        $this->validateOptions($options);
        $options['prefix'] = trim($options['prefix']);
        $options['token'] = trim($options['token']);
        $options['defaultPlugins'] = boolval(!empty($options['defaultPlugins']) ? $options['defaultPlugins'] : true);
        return $options;
    }

    public function registerPlugin(BasePlugin $plugin)
    {
        $class = get_class($plugin);
        if (isset($this->plugins[$class])) {
            user_error("Plugin ${class} is already registered. Skipping...", E_USER_WARNING);
            return;
        }

        $this->plugins[$class] = $plugin->init($this);
        foreach ($this->plugins[$class]->getCommands() as $command => $function) {
            if (isset($this->commands[$command])) {
                $oldClass = $this->commands[$command]['plugin'];
                echo "$class redefines $command, overwriting $oldClass" . PHP_EOL;
            }

            $this->commands[$command] = [
                'plugin' => $class,
                'function' => $function,
                'instance' => &$this->plugins[$class],
            ];
        }
    }

    protected function runCommand(string $command)
    {
        if (!isset($this->commands[$command])) {
            // TODO: decide whether or not we should send a reply here
            //       we could always just silently log it, then people triggering this on accident would be less spammy
            //       on the other hand, it's nice to get feedback when mistyping a command
            $this->sendMessage('Invalid command');
            return;
        }

        $function = $this->commands[$command]['function'];
        $instance = $this->commands[$command]['instance'];
        call_user_func([$instance, $function]);
    }

    public function sendMessage(string $message): bool
    {
        // TODO
        echo $message;
        return true;
    }
}