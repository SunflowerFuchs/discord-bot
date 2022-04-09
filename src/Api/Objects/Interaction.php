<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class Interaction
{
    public const TYPE_PING = 1;
    public const TYPE_APPLICATION_COMMAND = 2;
    public const TYPE_MESSAGE_COMPONENT = 3;

    /**
     * id of the interaction
     */
    protected Snowflake $id;
    /**
     * the type of interaction
     */
    protected int $type;
    /**
     * the name of the application command
     */
    protected string $name;
    /**
     * the user who invoked the interaction
     */
    protected User $user;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->type = $data['type'];
        $this->name = $data['name'];
        $this->user = new User($data['user']);
    }

    /**
     * id of the interaction
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * the type of interaction
     * @see Interaction::TYPE_PING
     * @see Interaction::TYPE_APPLICATION_COMMAND
     * @see Interaction::TYPE_MESSAGE_COMPONENT
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * the name of the application command
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * the user who invoked the interaction
     */
    public function getUser(): User
    {
        return $this->user;
    }


}