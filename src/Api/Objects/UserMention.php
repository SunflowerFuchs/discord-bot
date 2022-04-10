<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class UserMention
{
    protected User $user;
    protected ?GuildMember $member;

    public function __construct(array $data)
    {
        $memberData = [];
        if (!empty($data['member'])) {
            $memberData = $data['member'];
            unset($data['member']);
            $memberData['user'] = $data;
        }

        $this->user = new User($data);
        $this->member = !empty($memberData) ? new GuildMember($memberData) : null;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return ?GuildMember
     */
    public function getMember(): ?GuildMember
    {
        return $this->member;
    }
}