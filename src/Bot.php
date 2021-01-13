<?php

namespace SunflowerFuchs\DiscordBot;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message as RatchetMessage;
use React\EventLoop\Factory;
use React\EventLoop\StreamSelectLoop;
use React\EventLoop\TimerInterface;
use SunflowerFuchs\DiscordBot\ApiObjects\Message;
use SunflowerFuchs\DiscordBot\Helpers\EventManager;
use SunflowerFuchs\DiscordBot\Plugins\BasePlugin;
use SunflowerFuchs\DiscordBot\Plugins\PingPlugin;
use SunflowerFuchs\DiscordBot\Plugins\UptimePlugin;

class Bot
{
    protected const gatewayParams = '?v=8&encoding=json';
    protected const defaultPlugins = [
        PingPlugin::class,
        UptimePlugin::class,
    ];
    public const BaseImageUrl = 'https://cdn.discordapp.com/';

    protected array $options = [];
    protected array $plugins = [];
    protected array $commands = [];
    protected array $header = [
        'User-Agent' => 'DiscordBot (https://github.com/sunflowerfuchs/discord-bot, 0.1)',
        'Authorization' => 'Bot {Token}',
        'Content-Type' => 'multipart/form-data',
    ];
    protected int $sequence = 0;
    protected int $userId = 0;
    protected string $sessionId = '';
    protected bool $waitingForHeartbeatACK = false;
    protected bool $reconnect = false;
    protected ?EventManager $events = null;
    protected ?StreamSelectLoop $loop = null;
    protected ?WebSocket $websocket = null;
    protected ?TimerInterface $heartbeatTimer = null;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        static $instance;
        return $instance ?? ($instance = new self());
    }

    protected function initialize()
    {
        static $initialized = false;
        if (!$initialized) {
            if ($this->options['defaultPlugins']) {
                foreach (static::defaultPlugins as $class) {
                    $this->registerPlugin(new $class());
                }
            }
            $this->events = $this->initEventManager();

            $initialized = true;
        }
    }

    public function run(): bool
    {
        static $running = false;
        if ($running) {
            return false;
        }
        $running = true;

        $this->initialize();
        $this->invokeGateway();

        return true;
    }

    public function setOptions(array $options): void
    {
        $this->options = $this->cleanupOptions($options);

        $token = $this->options['token'];
        $this->header['Authorization'] = "Bot ${token}";
    }

    protected function validateOptions(array $options): void
    {
        $required = [
            'token',
            'prefix'
        ];
        $known = [
            ...$required,
            'defaultPlugins',
            'debug'
        ];
        $given = array_keys($options);

        $missing = array_diff($required, $given);
        if (!empty($missing)) {
            $missing = implode(', ', $missing);
            throw new InvalidArgumentException("Missing required argument(s): ${missing}");
        }

        $unknown = array_diff($given, $known);
        if (!empty($unknown)) {
            $unknownList = implode(', ', $unknown);
            user_error("Unknown argument(s): ${unknownList}", E_USER_WARNING);
            array_filter($options, fn($key) => !array_key_exists($key, $unknown), ARRAY_FILTER_USE_KEY);
        }

        // TODO: Add type validation
        //       I should probably do a whole rewrite of this function for that
    }

    protected function cleanupOptions(array $options): array
    {
        // TODO: In case i rewrite validateOptions, this either becomes obsolete, or should also be rewritten
        $this->validateOptions($options);
        $options['prefix'] = trim($options['prefix']);
        $options['token'] = trim($options['token']);
        $options['defaultPlugins'] = boolval(!empty($options['defaultPlugins']) ? $options['defaultPlugins'] : true);
        $options['debug'] = boolval($options['debug'] ?? false);
        return $options;
    }

    public function getPrefix(): string
    {
        return $this->options['prefix'];
    }

    protected function initEventManager(): EventManager
    {
        $manager = EventManager::getInstance();

        $manager->subscribe(EventManager::READY, function (array $message) {
            $this->debugMsg("Gateway session initialized.");
            $this->sessionId = $message['d']['session_id'];
            $this->userId = $message['d']['user']['id'];
        });

        $manager->subscribe(EventManager::MESSAGE_CREATE, function (array $message) {
            $msg = new Message($message['d']);
            if ($msg->isCommand()) {
                $this->runCommand($msg->getCommand(), $msg);
            }
        });

        return $manager;
    }

    public function subscribeToEvent(string $event, callable $handler): void
    {
        $this->events->subscribe($event, $handler);
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
                user_error("$class redefines $command, overwriting $oldClass", E_USER_WARNING);
            }

            $this->commands[$command] = [
                'plugin' => $class,
                'function' => $function,
                'instance' => &$this->plugins[$class],
            ];
        }
    }

    protected function runCommand(string $command, Message $messageObject)
    {
        // Handle unknown commands
        if (!isset($this->commands[$command])) {
            return;
        }

        // Parse which command to run and launch it
        $function = $this->commands[$command]['function'];
        $instance = $this->commands[$command]['instance'];
        call_user_func([$instance, $function], $messageObject);
    }

    public function sendMessage(string $message, string $channelId): bool
    {
        $res = $this->getApiClient()->post('channels/' . $channelId . '/messages', ([
            'multipart' => [
                [
                    'name' => 'content',
                    'contents' => $message,
                ],
                [
                    'name' => 'file',
                    'contents' => 'content',
                ],
            ],
        ]));

        if ($res->getStatusCode() != 200) {
            user_error("Sending message to channel ${channelId} failed", E_USER_WARNING);
            return false;
        }

        return true;
    }

    protected function invokeGateway()
    {
        static $gatewayUrl, $connector;
        if (!$this->loop) {
            $this->loop = Factory::create();
        }
        if (!$gatewayUrl) {
            $res = $this->getApiClient()->get('gateway', []);
            if ($res->getStatusCode() != 200) {
                throw new Exception('Error retrieving gateway');
            }

            $gatewayJson = json_decode($res->getBody()->getContents(), true);
            $gatewayUrl = $gatewayJson['url'];
            /* TODO: sharding
            // these params only come with if we GET gateway/bot/, which should not be cached
            $this->shards = $gatewayJson['shards'];
            $limits = $gatewayJson['session_start_limit'] ?? false;

            if ($limits && $limits['remaining'] < ($limits['total'] * 0.1)) {
                $resetInMinutes = $limits['reset_after'] / 1000 / 60;
                user_error('WARNING: Only ' . $limits['remaining'] . ' gateway connections remaining. Reset in ' . $resetInMinutes . ' minutes (' . ($resetInMinutes / 60) . ' hours).', E_USER_WARNING);
            }
            */
        }
        if (!$connector) {
            $connector = new Connector($this->loop);
        }

        $connector->__invoke($gatewayUrl . static::gatewayParams, [], $this->header)->then(function (
            WebSocket $conn
        ) {
            $this->debugMsg('Connected to gateway.');
            $this->websocket = $conn;
            $this->waitingForHeartbeatACK = false;

            $conn->on('message', fn($message) => $this->onGatewayMessage($message));
            $conn->on('error', fn() => $this->onGatewayError());
            $conn->on('close', fn($errorCode, $msg) => $this->onGatewayClose($errorCode, $msg));
        }, function (Exception $e) {
            user_error("Could not connect to gateway, reason: " . $e->getMessage(), E_USER_ERROR);
            die();
        });

        $this->loop->run();
    }

    protected function reconnectGateway(bool $sendReconnect = true)
    {
        $this->reconnect = $sendReconnect;
        $this->closeGateway(4100, 'Going to reconnect.');
        $this->invokeGateway();
    }

    protected function closeGateway(int $code = 4100, string $reason = '')
    {
        $this->removeHeartbeatTimer();

        // Not sure if removing the listeners manually is necessary, but i do it here for cleanliness
        $this->websocket->removeAllListeners();
        $this->websocket->close($code, $reason);
        $this->loop->stop();
    }

    protected function onGatewayMessage(RatchetMessage $receivedMessage)
    {
        $message = json_decode($receivedMessage->getPayload(), true);
        $this->sequence = $message['s'] ?? $this->sequence;

        switch ($message['op']) {
            case 10: //"10 Hello"-Payload
                $this->addHeartbeatTimer(floatval($message['d']['heartbeat_interval'] / 1000));
                $this->identify();
                break;
            case 1: // "1 Heartbeat"-Payload
                $this->sendHeartbeat();
                break;
            case 11: // "11 Heartbeat ACK"-Payload
                $this->waitingForHeartbeatACK = false;
                break;
            case 9: // "9 Invalid Session"-Payload
            case 7: // "7 Reconnect"-Payload
                $this->debugMsg('Reconnect payload received...');
                $this->reconnectGateway(boolval($message['d']) ?? true);
                break;
            case 0: // "0 Dispatch"-Payload
                $this->events->publish($message['t'], $message);
                break;
            default:
                user_error("Unknown Opcode ${message['op']} received.", E_USER_NOTICE);
                // var_dump( $message );
                // Unknown Opcode
                break;
        }
    }

    protected function onGatewayError()
    {
        user_error("Gateway sent an unexpected error, attempting to reconnect...", E_USER_WARNING);
        $this->reconnectGateway();
    }

    protected function onGatewayClose(int $errorCode, string $errorMessage)
    {
        user_error("Gateway was unexpectedly closed, reason: ${errorCode} - ${errorMessage}" . PHP_EOL
            . "Attempting to reconnect...", E_USER_WARNING);
        $this->reconnectGateway();
    }

    protected function identify()
    {
        if ($this->reconnect) {
            $this->reconnect = false;
            $this->debugMsg("Resuming...");
            $message = [
                'op' => 6,
                'd' => [
                    'token' => $this->options['token'],
                    'session_id' => $this->sessionId,
                    'seq' => $this->sequence,
                ],
                's' => null,
                't' => 'GATEWAY_RESUME',
            ];
        } else {
            $this->debugMsg("Identifying...");
            $message = [
                'op' => 2,
                'd' => [
                    'token' => $this->options['token'],
                    'intents' => $this->events->calculateIntent(),
                    'properties' => [
                        '$os' => PHP_OS,
                        '$browser' => $this->header['User-Agent'],
                        '$device' => $this->header['User-Agent'],
                        '$referrer' => '',
                        '$$referring_domain' => '',
                    ],
                    'compress' => false,
                    'large_threshold' => 250,
                    // TODO: add sharding
                    //'shard' => [1, $this->shards],
                ],
                's' => $this->sequence,
                't' => 'GATEWAY_IDENTIFY',
            ];
        }

        $this->websocket->send(json_encode($message));
    }

    protected function addHeartbeatTimer(float $interval)
    {
        if ($this->heartbeatTimer) {
            user_error('New HeartbeatTimer while we still have an old one. Should not happen...', E_USER_NOTICE);
            $this->removeHeartbeatTimer();
        }

        $this->heartbeatTimer = $this->loop->addPeriodicTimer($interval, fn() => $this->sendHeartbeat());
        // instantly send the first heartbeat
        $this->sendHeartbeat();
    }

    protected function removeHeartbeatTimer()
    {
        if (!$this->heartbeatTimer) {
            return;
        }

        $this->loop->cancelTimer($this->heartbeatTimer);
        $this->heartbeatTimer = null;
    }

    protected function sendHeartbeat()
    {
        if ($this->waitingForHeartbeatACK) {
            user_error("No ACK for Heartbeat received. Attempting to reconnect...", E_USER_NOTICE);
            $this->reconnectGateway();
        } else {
            $answer = [
                'op' => 1,
                'd' => $this->sequence,
            ];
            $this->websocket->send(json_encode($answer));
            $this->waitingForHeartbeatACK = true;
        }
    }

    public function getApiClient(): Client
    {
        static $apiClient;
        return $apiClient ?? ($apiClient = new Client([
                'base_uri' => 'https://discordapp.com/api/',
                'headers' => $this->header,
            ]));
    }

    public function debugMsg(string $message)
    {
        if (!$this->options['debug']) {
            return;
        }

        echo $message . PHP_EOL;
    }
}
