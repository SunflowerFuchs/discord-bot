<?php


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class MessageActivity
{
    const TYPE_JOIN = 1;
    const TYPE_SPECTATE = 2;
    const TYPE_LISTEN = 3;
    const TYPE_JOIN_REQUEST = 5;

    /**
     * type of message activity
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
     * @see MessageActivity::TYPE_JOIN
     * @see MessageActivity::TYPE_SPECTATE
     * @see MessageActivity::TYPE_LISTEN
     * @see MessageActivity::TYPE_JOIN_REQUEST
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