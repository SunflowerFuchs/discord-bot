<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Plugins;

use LogicException;
use ReflectionClass;
use RuntimeException;
use SunflowerFuchs\DiscordBot\Api\Objects\AllowedMentions;
use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use SunflowerFuchs\DiscordBot\Bot;

abstract class BasePlugin
{
    private const DEFAULT_TIMEOUT = 7.0;
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
        float $timeout = self::DEFAULT_TIMEOUT,
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
}