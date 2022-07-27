<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class Snowflake
{
    /**
     * The discord epoch which all snowflake-timestamps are based on
     *
     * The first second of 2015
     */
    public const DiscordEpoch = 1420070400000;

    protected string $snowflake;

    /**
     * Create a new Snowflake instance
     *
     * If you just need a snowflake string, see {@see Snowflake::stringFromTimestamp()}
     * @param string|null $snowflake Pass an existing snowflake string, or leave empty to use the current time
     */
    public function __construct(?string $snowflake = null)
    {
        $this->snowflake = $snowflake ?? self::stringFromTimestamp(intval(microtime(true) * 1000));
    }

    public function __toString(): string
    {
        return $this->snowflake;
    }

    public function toInt(): int
    {
        return (int)$this->snowflake;
    }

    /**
     * The timestamp of this snowflake in milliseconds
     *
     * The returned timestamp has already been adjusted to be a regular epoch timestamp,
     * instead of one based on the discord epoch
     *
     * @return int
     */
    public function getTimeStamp(): int
    {
        return ($this->snowflake >> 22) + static::DiscordEpoch;
    }

    /**
     * Internal worker ID
     *
     * @return int
     * @internal
     */
    public function getWorkerId(): int
    {
        return ($this->snowflake & 0x3E0000) >> 17;
    }

    /**
     * Internal process ID
     *
     * @return int
     * @internal
     */
    public function getProcessId(): int
    {
        return ($this->snowflake & 0x1F000) >> 12;
    }

    /**
     * Increment
     *
     * For every ID that is generated on that process, this number is incremented
     *
     * @return int
     */
    public function getIncrement(): int
    {
        return $this->snowflake & 0xFFF;
    }

    /**
     * Create an instance from the given snowflake string
     *
     * @param string $snowflake
     * @return static
     */
    public static function fromString(string $snowflake): self
    {
        return new static($snowflake);
    }

    /**
     * Generate a new Snowflake from a given epoch timestamp
     *
     * @param int $timestamp The timestamp in milliseconds (e.g. {@see microtime}(true) * 1000)
     * @return static
     */
    public static function fromTimestamp(int $timestamp): self
    {
        return self::fromString(self::stringFromTimestamp($timestamp));
    }

    /**
     * Generate a snowflake as a string from a timestamp
     * Useful if you just need the string without creating an instance
     *
     * @param int $timestamp The timestamp in milliseconds (e.g. {@see microtime}(true) * 1000)
     * @return string
     */
    public static function stringFromTimestamp(int $timestamp): string
    {
        return strval(($timestamp - self::DiscordEpoch) << 22);
    }
}