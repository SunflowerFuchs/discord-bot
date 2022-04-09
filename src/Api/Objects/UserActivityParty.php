<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class UserActivityParty
{
    /**
     * the id of the party
     */
    protected string $id;
    /**
     * the party's current size
     */
    protected int $current_size;
    /**
     * the party's maximum size
     */
    protected int $max_size;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        ['current_size' => $this->current_size, 'max_size' => $this->max_size,] = $data['size'] ?? [
            'current_size' => 0,
            'max_size' => 0
        ];
    }

    /**
     * the id of the party
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * the party's current size
     * @return int
     */
    public function getCurrentSize(): int
    {
        return $this->current_size;
    }

    /**
     * the party's maximum size
     * @return int
     */
    public function getMaxSize(): int
    {
        return $this->max_size;
    }


}