<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Plugins;

use Exception;
use LogicException;
use React\EventLoop\TimerInterface;
use ReflectionClass;
use RuntimeException;
use SunflowerFuchs\DiscordBot\Api\Constants\Events;
use SunflowerFuchs\DiscordBot\Api\Objects\AllowedMentions;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use SunflowerFuchs\DiscordBot\Bot;

abstract class BasePlugin
{
    private const DEFAULT_SELF_DESTRUCT_TIMER = 7.0;
    private const DEFAULT_RESPONSE_TIMEOUT = 120;
    private ?Bot $bot = null;

    abstract public function init();

    /**
     * @return Bot
     * @throws LogicException if called before plugin was correctly initialized
     */
    protected function getBot(): Bot
    {
        if (is_null($this->bot)) {
            // I'd love to properly log this but without the bot I can't, so throwing an exception is the best I can do
            throw new LogicException('Tried getting bot in plugin without initializing it correctly first');
        }

        return $this->bot;
    }

    /**
     * @param Bot $bot
     */
    public function setBot(Bot $bot): void
    {
        if (!is_null($this->bot)) {
            $this->bot->getLogger()->warning("Re-initialized plugin... Not an error, but most likely undesirable");
        }
        $this->bot = $bot;
    }

    public function getClassName(): string
    {
        return (new ReflectionClass(static::class))->getShortName();
    }

    protected function getDataDir(): string
    {
        $baseDir = $this->bot->getDataDir();
        $className = $this->getClassName();
        $fullPath = $baseDir . $className . DIRECTORY_SEPARATOR;

        if (is_dir($fullPath) || @mkdir($fullPath, 0775, true) === true) {
            return $fullPath;
        }
        throw new RuntimeException(sprintf('Could not create dataDir for plugin "%s"', $className));
    }

    /**
     * @throws Exception
     */
    protected function subscribeToEvent(string $event, callable $handler): string
    {
        return $this->getBot()->getEventManager()->subscribe($event, $handler);
    }

    protected function unsubscribeFromEvent(string $eventId): bool
    {
        return $this->getBot()->getEventManager()->unsubscribe($eventId);
    }

    protected function sendMessage(
        string $message,
        Snowflake $channelId,
        AllowedMentions $allowedMentions = null
    ): Message|string {
        return $this->getBot()->sendMessage($message, $channelId, $allowedMentions);
    }

    protected function sendSelfDestructingMessage(
        string $message,
        Snowflake $channelId,
        float $timeout = self::DEFAULT_SELF_DESTRUCT_TIMER,
        AllowedMentions $allowedMentions = null
    ): Message|string {
        $responseMsg = $this->getBot()->sendMessage($message, $channelId, $allowedMentions);
        if ($responseMsg instanceof Message) {
            $this->getBot()->getLoop()->addTimer($timeout, fn() => Message::delete(
                $this->getBot()->getApiClient(),
                $responseMsg->getId(),
                $responseMsg->getChannelId()
            ));
        }

        return $responseMsg;
    }

    /**
     * @throws Exception
     */
    protected function sendMessageAndAwaitResponse(
        string $message,
        Snowflake $channelId,
        Snowflake $senderId,
        callable $callback,
        AllowedMentions $allowedMentions = null,
        int $timeout = self::DEFAULT_RESPONSE_TIMEOUT
    ): Message|string {
        $return = $this->sendMessage($message, $channelId, $allowedMentions);
        if (!$return instanceof Message) {
            return $return;
        }

        /**
         * @var TimerInterface $timer
         * @var string $subscriptionId
         */
        $timer = $subscriptionId = null;
        $subscriptionId = $this->subscribeToEvent(Events::MESSAGE_CREATE,
            function (Message $message) use ($channelId, $senderId, $callback, &$timer, &$subscriptionId) {
                if (!$message->isUserMessage() || $message->getChannelId() != $channelId || $message->getAuthor()->getId() != $senderId) {
                    return false;
                }

                $remove = $callback($message);
                if ($remove) {
                    $this->unsubscribeFromEvent($subscriptionId);
                    $this->getBot()->getLoop()->cancelTimer($timer);
                }
                return true;
            }
        );
        $timer = $this->getBot()->getLoop()->addTimer($timeout, fn() => $this->unsubscribeFromEvent($subscriptionId));

        return $return;
    }
}