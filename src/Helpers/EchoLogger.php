<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class EchoLogger implements LoggerInterface
{
    use LoggerTrait;

    protected const ERROR_MAP = [
        LogLevel::EMERGENCY => 7,
        LogLevel::ALERT => 6,
        LogLevel::CRITICAL => 5,
        LogLevel::ERROR => 4,
        LogLevel::WARNING => 3,
        LogLevel::NOTICE => 2,
        LogLevel::INFO => 1,
        LogLevel::DEBUG => 0,
    ];

    protected int $minErrorLevel;

    public function __construct(string $minLogLevel = LogLevel::INFO)
    {
        if (!isset(static::ERROR_MAP[$minLogLevel])) {
            throw new InvalidArgumentException('Invalid loglevel passed');
        }

        $this->minErrorLevel = self::ERROR_MAP[$minLogLevel];
    }

    protected function showContext(): bool
    {
        return $this->minErrorLevel <= static::ERROR_MAP[LogLevel::DEBUG];
    }

    public function log($level, $message, array $context = array())
    {
        if (!isset(static::ERROR_MAP[$level])) {
            throw new InvalidArgumentException('Invalid loglevel passed');
        }

        if (static::ERROR_MAP[$level] < $this->minErrorLevel) {
            return;
        }

        if (!empty($context) && $this->showContext()) {
            $contextLines = explode("\n", var_export($context, true));
            unset($contextLines[0], $contextLines[array_key_last($contextLines)]); // for readability
            $message .= "\n" . implode("\n", $contextLines);
        }

        $date = date("Y-m-d H:i:s");
        echo "[${date}]\t[${level}]\t${message}\n";
    }
}