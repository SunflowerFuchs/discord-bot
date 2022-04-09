<?php


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use SunflowerFuchs\DiscordBot\Api\Constants\ChannelType;

class Mention
{
    /**
     * id of the channel
     */
    protected Snowflake $id;
    /**
     * id of the guild containing the channel
     */
    protected Snowflake $guild_id;
    /**
     * the type of channel
     */
    protected int $type;
    /**
     * the name of the channel
     */
    protected string $name;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->guild_id = new Snowflake($data['guild_id']);
        $this->type = intval($data['type']);
        $this->name = $data['name'];
    }

    /**
     * The id of the mentioned channel
     *
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * The id of the guild
     *
     * @return Snowflake
     */
    public function getGuildId(): Snowflake
    {
        return $this->guild_id;
    }

    /**
     * Returns the type of the channel
     *
     * @return int
     * @see ChannelType
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * The name of the channel
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}