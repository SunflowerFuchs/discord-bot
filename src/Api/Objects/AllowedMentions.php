<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\AllowedMentionType;

class AllowedMentions
{
    /**
     * An array of allowed mention types to parse from the content.
     * @see AllowedMentionType
     * @var string[]
     */
    protected array $parse = [];
    /**
     * Array of role_ids to mention (Max size of 100)
     * @var Snowflake[]
     */
    protected array $roles = [];
    /**
     * Array of user_ids to mention (Max size of 100)
     * @var Snowflake[]
     */
    protected array $users = [];
    /**
     * For replies, whether to mention the author of the message being replied to (default false)
     */
    protected bool $replied_user = false;

    /**
     * Allow this message to mention the author of the message that's being replied to
     *
     * @return self
     */
    public function mentionReply(): self
    {
        $this->replied_user = true;
        return $this;
    }

    /**
     * Allow this message to mention @ everyone and @ here
     *
     * @return self
     */
    public function allowEveryone(): self
    {
        $this->parse[] = AllowedMentionType::EVERYONE;
        return $this;
    }

    /**
     * Allow this message to mention any user
     *
     * @return self
     */
    public function allowAllUsers(): self
    {
        $this->parse[] = AllowedMentionType::USERS;
        return $this;
    }

    /**
     * Allow this message to mention any role
     *
     * @return self
     */
    public function allowAllRoles(): self
    {
        $this->parse[] = AllowedMentionType::ROLES;
        return $this;
    }

    /**
     * Allow this message to mention the given user
     *
     * @param Snowflake $userId
     *
     * @return self
     */
    public function allowUser(Snowflake $userId): self
    {
        $this->users[] = $userId;
        return $this;
    }

    /**
     * Allow this message to mention the given role
     *
     * @param Snowflake $roleId
     *
     * @return self
     */
    public function allowRole(Snowflake $roleId): self
    {
        $this->roles[] = $roleId;
        return $this;
    }

    /**
     * Returns an array representation for use with the api
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [
            'parse' => $this->parse
        ];
        if (!empty($this->users)) {
            $array['users'] = array_map(fn(Snowflake $userId) => (string)$userId, $this->users);
        }
        if (!empty($this->roles)) {
            $array['roles'] = array_map(fn(Snowflake $roleId) => (string)$roleId, $this->roles);
        }
        if ($this->replied_user) {
            $array['replied_user'] = true;
        }

        return $array;
    }
}