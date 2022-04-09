<?php

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\UserActivityFlag;

class UserActivity
{
    /**
     * the activity's name
     */
    protected string $name;
    /**
     * activity type
     */
    protected int $type;
    /**
     * stream url, is validated when type is 1
     */
    protected string $url;
    /**
     * unix timestamp (in milliseconds) of when the activity was added to the user's session
     */
    protected int $created_at;
    /**
     * unix timestamps for start and/or end of the game
     */
    protected ?UserActivityTimestamp $timestamps;
    /**
     * application id for the game
     */
    protected ?Snowflake $application_id;
    /**
     * what the player is currently doing
     */
    protected string $details;
    /**
     * the user's current party status
     */
    protected string $state;
    /**
     * the emoji used for a custom status
     */
    protected ?UserActivityEmoji $emoji;
    /**
     * information for the current party of the player
     */
    protected ?UserActivityParty $party;
    /**
     * images for the presence and their hover texts
     */
    protected ?UserActivityAssets $assets;
    /**
     * secrets for Rich Presence joining and spectating
     */
    protected ?UserActivitySecrets $secrets;
    /**
     * whether the activity is an instanced game session
     */
    protected bool $instance;
    /**
     * activity flags ORd together, describes what the payload includes
     * @see UserActivityFlag
     */
    protected int $flags;
    /**
     * the custom buttons shown in the Rich Presence (max 2)
     * @var UserActivityButton[]
     */
    protected array $buttons;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->url = $data['url'] ?? '';
        $this->created_at = $data['created_at'];
        $this->timestamps = !empty($data['timestamps']) ? new UserActivityTimestamp($data['timestamps']) : null;
        $this->application_id = !empty($data['application_id']) ? new Snowflake($data['application_id']) : null;
        $this->details = $data['details'] ?? '';
        $this->state = $data['state'] ?? '';
        $this->emoji = !empty($data['emoji']) ? new UserActivityEmoji($data['emoji']) : null;
        $this->party = !empty($data['party']) ? new UserActivityParty($data['party']) : null;
        $this->assets = !empty($data['assets']) ? new UserActivityAssets($data['assets']) : null;
        $this->secrets = !empty($data['secrets']) ? new UserActivitySecrets($data['secrets']) : null;
        $this->instance = $data['instance'] ?? false;
        $this->flags = $data['flags'] ?? 0;
        $this->buttons = array_map(fn(array $buttonData) => new UserActivityButton($buttonData),
            $data['buttons'] ?? []);
    }

    /**
     * the activity's name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * activity type
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * stream url, is validated when type is 1
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * unix timestamp (in milliseconds) of when the activity was added to the user's session
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * unix timestamps for start and/or end of the game
     * @return ?UserActivityTimestamp
     */
    public function getTimestamps(): ?UserActivityTimestamp
    {
        return $this->timestamps;
    }

    /**
     * application id for the game
     * @return ?Snowflake
     */
    public function getApplicationId(): ?Snowflake
    {
        return $this->application_id;
    }

    /**
     * what the player is currently doing
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * the user's current party status
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * the emoji used for a custom status
     * @return ?UserActivityEmoji
     */
    public function getEmoji(): ?UserActivityEmoji
    {
        return $this->emoji;
    }

    /**
     * information for the current party of the player
     * @return ?UserActivityParty
     */
    public function getParty(): ?UserActivityParty
    {
        return $this->party;
    }

    /**
     * images for the presence and their hover texts
     * @return ?UserActivityAssets
     */
    public function getAssets(): ?UserActivityAssets
    {
        return $this->assets;
    }

    /**
     * secrets for Rich Presence joining and spectating
     * @return ?UserActivitySecrets
     */
    public function getSecrets(): ?UserActivitySecrets
    {
        return $this->secrets;
    }

    /**
     * whether the activity is an instanced game session
     * @return bool
     */
    public function isInstance(): bool
    {
        return $this->instance;
    }

    /**
     * activity flags ORd together, describes what the payload includes
     * @return int
     */
    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * the custom buttons shown in the Rich Presence (max 2)
     * @return UserActivityButton[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }


}