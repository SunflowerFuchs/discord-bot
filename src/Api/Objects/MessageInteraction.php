<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class MessageInteraction
{
    /**
     * id of the interaction
     */
    protected Snowflake $id;
    /**
     * the type of interaction
     * @see InteractionType
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
    /**
     * the member who invoked the interaction in the guild
     */
    protected ?GuildMember $member;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->type = $data['type'];
        $this->name = $data['name'];
        $this->user = new User($data['user']);
        $this->member = !empty($data['member']) ? new GuildMember($data['member']) : null;
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
     * @see InteractionType
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