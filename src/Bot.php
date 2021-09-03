<?php

namespace SunflowerFuchs\DiscordBot;

use Exception;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message as RatchetMessage;
use React\EventLoop\Factory;
use React\EventLoop\StreamSelectLoop;
use React\EventLoop\TimerInterface;
use SunflowerFuchs\DiscordBot\ApiObjects\Message;
use SunflowerFuchs\DiscordBot\Helpers\BotOptions;
use SunflowerFuchs\DiscordBot\Helpers\EchoLogger;
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

class Bot implements LoggerAwareInterface
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
    protected EventManager $eventManager;
    protected StreamSelectLoop $loop;
    protected LoggerInterface $logger;
    protected ?WebSocket $websocket = null;
    protected ?TimerInterface $heartbeatTimer = null;

    private function __construct()
    {
        $this->logger = new EchoLogger();
        $this->eventManager = EventManager::getInstance();
        $this->loop = Factory::create();
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

            $this->initEventManager();

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

        if ($this->logger instanceof EchoLogger) {
            $this->setLogger(new EchoLogger($this->options['loglevel']));
        }
        $this->logger->debug("Options set", ['options' => $this->options]);
    }

    public function getPrefix(): string
    {
        return $this->options['prefix'];
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function initEventManager(): void
    {
        $this->eventManager->subscribe(EventManager::READY, function (array $message) {
            $this->logger->info("Gateway session initialized.");
            $this->sessionId = $message['d']['session_id'];
            $this->userId = $message['d']['user']['id'];
        });

        $this->eventManager->subscribe(EventManager::MESSAGE_CREATE, function (array $message) {
            $msg = new Message($message['d']);
            if ($msg->isCommand()) {
                $this->runCommand($msg->getCommand(), $msg);
            }
        });
    }

    public function subscribeToEvent(string $event, callable $handler): void
    {
        $this->eventManager->subscribe($event, $handler);
    }

    public function registerPlugin(BasePlugin $plugin): bool
    {
        $class = get_class($plugin);
        if (isset($this->plugins[$class])) {
            $this->logger->warning("Plugin ${class} is already registered. Skipping...",
                ['Loaded Plugins' => array_keys($this->plugins)]);
            return false;
        }

        $this->plugins[$class] = $plugin;
        $this->plugins[$class]->init();
        return true;
    }

    public function registerCommand(string $command, callable $handler): bool
    {
        if (isset($this->commands[$command])) {
            $this->logger->warning("Command '${command}' already registered. Skipping...",
                ['Registered Commands' => array_keys($this->commands)]);
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
            $this->logger->warning("Sending message to channel ${channelId} failed");
            return false;
        }

        return true;
    }

    protected function invokeGateway()
    {
        static $gatewayUrl, $connector;
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
                $this->logger->warning('Only ' . $limits['remaining'] . ' gateway connections remaining. Reset in ' . $resetInMinutes . ' minutes (' . ($resetInMinutes / 60) . ' hours).');
            }
            */
        }
        if (!$connector) {
            $connector = new Connector($this->loop);
        }

        $connector->__invoke($gatewayUrl . static::gatewayParams, [], $this->header)->then(function (
            WebSocket $conn
        ) {
            $this->logger->info('Connected to gateway.');
            $this->websocket = $conn;
            $this->waitingForHeartbeatACK = false;

            $conn->on('message', fn($message) => $this->onGatewayMessage($message));
            $conn->on('error', fn() => $this->onGatewayError());
            $conn->on('close', fn($errorCode, $msg) => $this->onGatewayClose($errorCode, $msg));
        }, function (Exception $e) {
            $this->logger->error("Could not connect to gateway, reason: " . $e->getMessage());
            exit(1);
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
                $this->logger->info('Reconnect payload received...');
                $this->reconnectGateway(boolval($message['d'] ?? true));
                break;
            case 0: // "0 Dispatch"-Payload
                $this->eventManager->publish($message['t'], $message);
                break;
            default:
                $this->logger->notice("Unknown Opcode ${message['op']} received.");
                // Unknown Opcode
                break;
        }
    }

    protected function onGatewayError()
    {
        $this->logger->warning("Gateway sent an unexpected error, attempting to reconnect...");
        $this->reconnectGateway();
    }

    protected function onGatewayClose(int $errorCode, string $errorMessage)
    {
        $this->logger->warning("Gateway was unexpectedly closed, reason: ${errorCode} - ${errorMessage}");
        $this->logger->info("Attempting to reconnect after unexpected disconnect...");
        $this->reconnectGateway();
    }

    protected function identify()
    {
        if ($this->reconnect) {
            $this->reconnect = false;
            $this->logger->info("Resuming...");
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
            $this->logger->info("Identifying...");
            $message = [
                'op' => 2,
                'd' => [
                    'token' => $this->options['token'],
                    'intents' => $this->eventManager->calculateIntent(),
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
            $this->logger->notice('New HeartbeatTimer while we still have an old one. Should not happen...');
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
            $this->logger->notice("No ACK for Heartbeat received. Attempting to reconnect...");
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
}
