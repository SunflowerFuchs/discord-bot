<?php

namespace SunflowerFuchs\DiscordBot;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;
use React\EventLoop\Factory;
use React\EventLoop\StreamSelectLoop;
use React\Promise\PromiseInterface;
use SunflowerFuchs\DiscordBot\Plugins\BasePlugin;
use SunflowerFuchs\DiscordBot\Plugins\PingPlugin;

class Bot
{
    protected const gatewayParams = '?v=8&encoding=json';
    protected const defaultPlugins = [
        PingPlugin::class,
    ];

    protected array $options;
    protected array $plugins = [];
    protected array $commands = [];
    protected array $header = [
        'User-Agent' => 'DiscordBot (https://github.com/sunflowerfuchs/discord-bot, 0.1)',
        'Authorization' => 'Bot {Token}',
        'Content-Type' => 'multipart/form-data',
    ];
    protected int $intents =
        1 << 9
        | 1 << 10
        | 1 << 11;
    protected int $sequence = 0;
    protected int $userId = 0;
    protected string $sessionId = '';
    protected bool $waitingForHeartbeatACK = false;
    protected bool $reconnect = false;
    protected StreamSelectLoop $loop;
    protected Connector $connector;
    protected PromiseInterface $socket;
    protected WebSocket $websocket;
    protected Client $apiClient;

    public function __construct(array $options)
    {
        $this->setOptions($options);

        if ($this->options['defaultPlugins']) {
            foreach (static::defaultPlugins as $class) {
                $this->registerPlugin(new $class());
            }
        }

        $this->loop = Factory::create();
        $this->connector = new Connector($this->loop);
    }

    public function run()
    {
        static $running = false;
        if ($running) return;
        $running = true;

        $this->invokeGateway();
        $this->loop->run();
    }

    protected function setOptions(array $options): void
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
            $unknown = implode(', ', $unknown);
            user_error("Unknown argument(s): ${unknown}", E_USER_WARNING);
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

        $this->intents |= $plugin->intents;
    }

    protected function runCommand(string $command, string $message, int $channelId)
    {
        // Handle unknown commands
        if (!isset($this->commands[$command])) return;

        // Parse which command to run and launch it
        $function = $this->commands[$command]['function'];
        $instance = $this->commands[$command]['instance'];
        call_user_func([$instance, $function], $message, $channelId);
    }

    public function sendMessage(string $message, int $channelId): bool
    {
        $res = $this->apiClient->post('channels/' . $channelId . '/messages', ([
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
            echo "Couldn't send message to channel." . PHP_EOL;
            echo "ChannelID: " . $channelId . PHP_EOL;
            echo "Text: " . $message . PHP_EOL;
            return false;
        }

        return true;
    }

    function invokeGateway()
    {
        $this->apiClient = new Client([
            'base_uri' => 'https://discordapp.com/api/',
            'headers' => $this->header,
        ]);
        $res = $this->apiClient->request('GET', 'gateway/bot', []);

        if ($res->getStatusCode() != 200) {
            throw new Exception('Error retrieving gateway');
        }

        $gatewayJson = json_decode($res->getBody()->getContents(), true);
        $gatewayUrl = $gatewayJson['url'];
        $limits = $gatewayJson['session_start_limit'] ?? false;
        // TODO: sharding
        // $this->shards = $gatewayJson['shards'];

        if ($limits && $limits['remaining'] < ($limits['total'] * 0.1)) {
            $resetInMinutes = $limits['reset_after'] / 1000 / 60;
            echo 'WARNING: Only ' . $limits['remaining'] . ' gateway connections remaining. Reset in ' . $resetInMinutes . ' minutes (' . ($resetInMinutes / 60) . ' hours).' . PHP_EOL;
        }

        $this->socket = $this->connector->__invoke($gatewayUrl . static::gatewayParams, [], $this->header)->then(function (WebSocket $conn) {
            echo 'Connected!' . PHP_EOL;
            $this->websocket = $conn;
            $this->waitingForHeartbeatACK = false;

            $conn->on('message', [$this, 'onGatewayMessage']);
            $conn->on('error', [$this, 'onGatewayError']);
            $conn->on('close', [$this, 'onGatewayClose']);
        }, function (Exception $e) {
            echo "Could not connect:" . PHP_EOL;
            echo "Reason: " . $e->getMessage() . PHP_EOL;
            die();
        });
    }

    function reconnectGateway(bool $sendReconnect = true)
    {
        $this->reconnect = $sendReconnect;
        $this->closeGateway(1002, 'Going to reconnect.');
        $this->invokeGateway();
    }

    function closeGateway(int $code = 1001, string $reason = '')
    {
        $this->websocket->close($code, $reason);
    }

    function onGatewayMessage(Message $receivedMessage)
    {
        $message = json_decode($receivedMessage->getPayload(), true);
        $this->sequence = $message['s'] ?? $this->sequence;

        if ($this->options['debug']) {
            echo 'Received message with Opcode ' . $message['op'] . PHP_EOL;
        }

        switch ($message['op']) {
            case 10: //"10 Hello"-Payload
                $this->sendHeartbeat();
                $heartbeatInterval = floatval($message['d']['heartbeat_interval'] / 1000);
                $this->loop->addPeriodicTimer($heartbeatInterval, [$this, 'sendHeartbeat']);
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
                $this->reconnectGateway($message['d']);
                break;
            case 0: // "0 Dispatch"-Payload
                switch ($message['t']) {
                    case 'READY' :
                        echo "Identified." . PHP_EOL;
                        $this->sessionId = $message['d']['session_id'];
                        $this->userId = $message['d']['user']['id'];
                        break;
                    case 'MESSAGE_CREATE':
                        if (strpos(trim($message['d']['content']), $this->options['prefix']) === 0) {
                            $content = substr(trim($message['d']['content']), strlen($this->options['prefix']));
                            $parts = explode(' ', $content);

                            $this->runCommand($parts[0], $parts[1] ?? '', $message['d']['channel_id']);
                        }
                        break;
                    case 'GUILD_CREATE':
                        // on connect with server
                        break;
                }
                break;
            default:
                user_error("Unknown Opcode ${message['op']} received.");
                // var_dump( $message );
                // Unknown Opcode
                break;
        }
    }

    function onGatewayError()
    {
        echo "Gateway sent an unexpected error!" . PHP_EOL;
        $this->reconnectGateway();
    }

    function onGatewayClose(int $errorCode, string $errorMessage)
    {
        echo "Gateway was closed!" . PHP_EOL;
        echo "Error code: $errorCode" . PHP_EOL;
        echo "Error message: $errorMessage" . PHP_EOL;
        $this->reconnectGateway();
    }

    function identify()
    {
        if ($this->reconnect) {
            $this->reconnect = false;
            echo "Reconnecting..." . PHP_EOL;
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
            echo "Identifying..." . PHP_EOL;
            $message = [
                'op' => 2,
                'd' => [
                    'token' => $this->options['token'],
                    'intents' => $this->intents,
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

    function sendHeartbeat()
    {
        if ($this->waitingForHeartbeatACK) {
            echo "No ACK for Heartbeat received." . PHP_EOL;
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
}