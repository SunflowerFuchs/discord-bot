<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class UserActivityTimestamp
{
    /**
     * unix time (in milliseconds) of when the activity started
     */
    protected int $start;
    /**
     * unix time (in milliseconds) of when the activity ends
     */
    protected int $end;

    public function __construct(array $data)
    {
        $this->start = $data['start'] ?? 0;
        $this->end = $data['end'] ?? 0;
    }

    /**
     * unix time (in milliseconds) of when the activity started
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * unix time (in milliseconds) of when the activity ends
     * @return int
     */
    public function getEnd(): int
    {
        return $this->end;
    }


}