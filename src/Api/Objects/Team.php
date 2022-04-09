<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class Team
{
    /**
     * a hash of the image of the team's icon
     */
    protected ?string $icon;
    /**
     * the unique id of the team
     */
    protected Snowflake $id;
    /**
     * the members of the team
     * @var TeamMember[]
     */
    protected array $members;
    /**
     * the name of the team
     */
    protected string $name;
    /**
     * the user id of the current team owner
     */
    protected Snowflake $owner_user_id;

    public function __construct(array $data)
    {
        $this->icon = $data['icon'] ?? null;
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->owner_user_id = new Snowflake($data['owner_user_id']);

        $this->members = array_map(fn(array $memberData) => new TeamMember($memberData),
            $data['members']);
    }

    /**
     * a hash of the image of the team's icon
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * the unique id of the team
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * the members of the team
     * @return TeamMember[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * the name of the team
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * the user id of the current team owner
     * @return Snowflake
     */
    public function getOwnerUserId(): Snowflake
    {
        return $this->owner_user_id;
    }


}