<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class IntegrationAccount
{
    /**
     * id of the account
     */
    protected string $id;
    /**
     * name of the account
     */
    protected string $name;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
    }

    /**
     * id of the account
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * name of the account
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}