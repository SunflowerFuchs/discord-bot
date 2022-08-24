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
use SunflowerFuchs\DiscordBot\Api\Objects\Emoji;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Reaction;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use SunflowerFuchs\DiscordBot\Bot;

abstract class BasePlugin
{
    private const DEFAULT_SELF_DESTRUCT_TIMER = 7.0;
    private const DEFAULT_RESPONSE_TIMEOUT = 120;
    private ?Bot $bot = null;

    abstract public function init();

    /**
     * Overwrite to run tasks once the bot connects.
     *
     * This function gets called automatically once the bot has connected successfully for the first time.
     * Unlike {@see init()}, this means that things like e.g. the api client are already available.
     *
     * @return void
     */
    public function ready()
    {
    }

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

    /**
     * Add a reaction to a message, and (if given) call a callback when someone adds to that reaction
     *
     * Note: Due to technical limitations this function only works when there is at least one subscriber
     * for {@see Events::MESSAGE_REACTION_ADD}, because the event only gets sent to the bot if that is the case.
     *
     * @param string $emoji
     * @param Snowflake $messageId
     * @param Snowflake $channelId
     * @param callable|null $onReaction
     * @return bool
     * @throws Exception
     */
    protected function addReaction(
        string $emoji,
        Snowflake $messageId,
        Snowflake $channelId,
        callable $onReaction = null
    ): bool {
        $emoji = Emoji::normalizeEmoji($emoji);

        $success = Reaction::create(
            $this->getBot()->getApiClient(),
            $channelId,
            $messageId,
            $emoji
        );

        if ($success && $onReaction !== null) {
            $this->subscribeToEvent(
                Events::MESSAGE_REACTION_ADD,
                function (array $data) use ($messageId, $emoji, $onReaction) {
                    $reactionMessageId = new Snowflake($data['message_id']);
                    $reactionEmoji = new Emoji($data['emoji']);

                    $emojiName = $reactionEmoji->getName();
                    $emojiId = $reactionEmoji->getId();
                    $isSameEmoji = Emoji::isStandardEmoji($emoji)
                        ? ($reactionEmoji->getId() === null && $emoji === $reactionEmoji->getName())
                        : ($emoji === "<a:${emojiName}:${emojiId}>" || $emoji === "<:${emojiName}:${emojiId}>");
                    if ($reactionMessageId != $messageId || !$isSameEmoji) {
                        return false;
                    }

                    return call_user_func($onReaction, $data);
                }
            );
        }

        return $success;
    }
}