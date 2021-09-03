<?php

namespace SunflowerFuchs\DiscordBot;

use Exception;
use GuzzleHttp\Client;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message as RatchetMessage;
use React\EventLoop\Factory;
use React\EventLoop\StreamSelectLoop;
use React\EventLoop\TimerInterface;
use SunflowerFuchs\DiscordBot\ApiObjects\Message;
use SunflowerFuchs\DiscordBot\Helpers\BotOptions;
use SunflowerFuchs\DiscordBot\Helpers\EventManager;
use SunflowerFuchs\DiscordBot\Plugins\BasePlugin;
use SunflowerFuchs\DiscordBot\Plugins\PingPlugin;
use SunflowerFuchs\DiscordBot\Plugins\UptimePlugin;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

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
    protected bool $keepRunning = false;
    protected bool $reconnect = false;
    protected bool $waitingForHeartbeatACK = false;
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

            $this->header['Authorization'] = 'Bot ' . $this->options['token'];

            $this->events = $this->initEventManager();

            $initialized = true;
        }
    }

    public function run(): bool
    {
        if ($this->keepRunning) {
            // The bot is already running
            return false;
        }
        $this->keepRunning = true;

        $this->initialize();
        do {
            $this->invokeGateway();
        } while ($this->keepRunning);

        return true;
    }

    public function stop(): bool
    {
        $this->keepRunning = false;
        $this->closeGateway();
        return true;
    }

    /**
     * @param array $options
     *
     * @throws UndefinedOptionsException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws OptionDefinitionException
     * @throws NoSuchOptionException
     * @throws AccessException
     */
    public function setOptions(array $options): void
    {
        // Moved the OptionsResolver into its own class for readability
        $this->options = (new BotOptions())->resolve($options);

        $redacted = array_replace($this->options, ['token' => 'XXX']);
        $flattened = var_export($redacted, true);
        $this->log("Bot initialized with the following options: ${flattened}", LOG_DEBUG);
    }

    public function getPrefix(): string
    {
        return $this->options['prefix'];
    }

    protected function initEventManager(): EventManager
    {
        $manager = EventManager::getInstance();

        $manager->subscribe(EventManager::READY, function (array $message) {
            $this->log("Gateway session initialized.");
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

        $this->plugins[$class] = $plugin;
        $this->plugins[$class]->init();
    }

    public function registerCommand(string $command, callable $handler): bool
    {
        if (isset($this->commands[$command])) {
            user_error("Command '${command}' already registered", E_USER_ERROR);
            return false;
        }

        $this->commands[$command] = $handler;
        return true;
    }

    protected function runCommand(string $command, Message $messageObject)
    {
        // Handle unknown commands
        if (!isset($this->commands[$command])) {
            return;
        }

        call_user_func($this->commands[$command], $messageObject);
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
            $this->log('Connected to gateway.');
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
                $this->log('Reconnect payload received...');
                $this->reconnectGateway(boolval($message['d'] ?? true));
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
        user_error("Gateway was unexpectedly closed, reason: ${errorCode} - ${errorMessage}", E_USER_WARNING);
        $this->log("Attempting to reconnect after unexpected disconnect...");
        $this->reconnectGateway();
    }

    protected function identify()
    {
        if ($this->reconnect) {
            $this->reconnect = false;
            $this->log("Resuming...");
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
            $this->log("Identifying...");
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

    protected function log(string $message, int $errorLevel = LOG_INFO)
    {
        if ($errorLevel > $this->options['loglevel']) {
            return;
        }

        $date = date("Y-m-d H:i:s");
        echo "[${date}]\t${message}\n";

        if ($errorLevel <= LOG_ERR) {
            exit($errorLevel + 1);
        }
    }
}
