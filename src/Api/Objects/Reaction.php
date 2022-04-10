<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


class Reaction
{
    /**
     * times this emoji has been used to react
     */
    protected int $count;
    /**
     * whether the current user reacted using this emoji
     */
    protected bool $me;
    /**
     * emoji information
     */
    protected Emoji $emoji;

    public function __construct(array $data)
    {
        $this->count = $data['count'];
        $this->me = $data['me'];
        $this->emoji = new Emoji($data['emoji']);
    }

    /**
     * The amount of reactions
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Whether the current user has reacted to this
     * @return bool
     */
    public function isMyReaction(): bool
    {
        return $this->me;
    }

    /**
     * partial emoji data
     * @return Emoji
     */
    public function getEmoji(): Emoji
    {
        return $this->emoji;
    }
}