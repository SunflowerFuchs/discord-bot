<?php


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use SunflowerFuchs\DiscordBot\Api\Constants\MessageActivityType;

class MessageActivity
{
    /**
     * type of message activity
     * @see MessageActivityType
     */
    protected int $type;
    /**
     * party_id from a Rich Presence event
     */
    protected ?string $party_id;

    public function __construct(array $data)
    {
        $this->type = $data['type'];
        $this->party_id = $data['party_id'] ?? null;
    }

    /**
     * the type of the message activity
     * @return int
     * @see MessageActivityType
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * the party id from a rich presence event
     * @return ?string
     */
    public function getPartyId(): ?string
    {
        return $this->party_id;
    }
}