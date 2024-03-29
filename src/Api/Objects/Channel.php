<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;


use GuzzleHttp\Client;
use SunflowerFuchs\DiscordBot\Api\Constants\ChannelType;
use SunflowerFuchs\DiscordBot\Api\Constants\Permissions;
use SunflowerFuchs\DiscordBot\Api\Constants\VideoQuality;
use SunflowerFuchs\DiscordBot\Bot;

class Channel
{
    /**
     * the id of this channel
     */
    protected Snowflake $id;
    /**
     * the type of channel
     * @see ChannelType
     */
    protected int $type;
    /**
     * the id of the guild
     */
    protected ?Snowflake $guild_id;
    /**
     * sorting position of the channel
     */
    protected int $position;
    /**
     * explicit permission overwrites for members and roles
     * @var Overwrite[]
     */
    protected array $permission_overwrites;
    /**
     * the name of the channel (2-100 characters)
     */
    protected string $name;
    /**
     * the channel topic (0-1024 characters)
     */
    protected ?string $topic;
    /**
     * whether the channel is nsfw
     */
    protected bool $nsfw;
    /**
     * the id of the last message sent in this channel (may not point to an existing or valid message)
     */
    protected ?Snowflake $last_message_id;
    /**
     * the bitrate (in bits) of the voice channel
     */
    protected int $bitrate;
    /**
     * the user limit of the voice channel
     * 0 is no limit
     */
    protected int $user_limit;
    /**
     * amount of seconds a user has to wait before sending another message (0-21600)
     * bots, as well as users with the permission {@see Permissions::MANAGE_MESSAGES} or {@see Permissions::MANAGE_CHANNELS}, are unaffected
     */
    protected int $rate_limit_per_user;
    /**
     * the recipients of the DM
     * @var User[]
     */
    protected array $recipients;
    /**
     * icon hash
     */
    protected ?string $icon;
    /**
     * id of the DM creator
     */
    protected ?Snowflake $owner_id;
    /**
     * application id of the group DM creator if it is bot-created
     */
    protected ?Snowflake $application_id;
    /**
     * id of the parent category for a channel (each parent category can contain up to 50 channels)
     */
    protected ?Snowflake $parent_id;
    /**
     * when the last pinned message was pinned.
     * This may be null in events such as {@see Permissions::GUILD_CREATE} when a message is not pinned.
     */
    protected ?int $last_pin_timestamp;
    /**
     * {@see VoiceRegion} id for the voice channel, automatic when set to null
     */
    protected ?string $rtc_region;
    /**
     * the camera video quality mode of the voice channel, {@see VideoQuality::AUTO} when not present
     * @see VideoQuality
     */
    protected int $video_quality_mode;
    /**
     * an approximate count of messages in a thread, stops counting at 50
     */
    protected ?int $message_count;
    /**
     * an approximate count of users in a thread, stops counting at 50
     */
    protected ?int $member_count;
    /**
     * thread-specific fields not needed by other channels
     */
    protected ?ThreadMetadata $thread_metadata;
    /**
     * thread member object for the current user, if they have joined the thread,
     * only included on certain API endpoints
     */
    protected ?ThreadMember $member;
    /**
     * default duration for newly created threads, in minutes, to automatically archive
     * the thread after recent activity, can be set to: 60, 1440, 4320, 10080
     */
    protected ?int $default_auto_archive_duration;
    /**
     * computed permissions for the invoking user in the channel, including overwrites,
     * only included when part of the resolved data received on a slash command interaction
     */
    protected ?string $permissions;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->type = $data['type'];

        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->position = $data['position'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->topic = $data['topic'] ?? null;
        $this->nsfw = $data['nsfw'] ?? false;
        $this->last_message_id = !empty($data['last_message_id']) ? new Snowflake($data['last_message_id']) : null;
        $this->bitrate = $data['bitrate'] ?? 0;
        $this->user_limit = $data['user_limit'] ?? 0;
        $this->rate_limit_per_user = $data['rate_limit_per_user'] ?? 0;
        $this->icon = $data['icon'] ?? null;
        $this->owner_id = !empty($data['owner_id']) ? new Snowflake($data['owner_id']) : null;
        $this->application_id = !empty($data['application_id']) ? new Snowflake($data['application_id']) : null;
        $this->parent_id = !empty($data['parent_id']) ? new Snowflake($data['parent_id']) : null;
        $this->last_pin_timestamp = !empty($data['last_pin_timestamp']) ? strtotime($data['last_pin_timestamp']) : null;
        $this->rtc_region = $data['rtc_region'] ?? null;
        $this->video_quality_mode = $data['video_quality_mode'] ?? VideoQuality::AUTO;
        $this->message_count = $data['message_count'] ?? null;
        $this->member_count = $data['member_count'] ?? null;
        $this->thread_metadata = !empty($data['thread_metadata']) ? new ThreadMetadata($data['thread_metadata']) : null;
        $this->member = !empty($data['member']) ? new ThreadMember($data['member']) : null;
        $this->default_auto_archive_duration = $data['default_auto_archive_duration'] ?? null;
        $this->permissions = $data['permissions'] ?? null;

        $this->permission_overwrites = array_map(fn(array $overwrite) => new Overwrite($overwrite),
            $data['permission_overwrites'] ?? []);
        $this->recipients = array_map(fn(array $recipient) => new User($recipient),
            $data['recipients'] ?? []);
    }

    public static function loadById(Client $apiClient, Snowflake $channelId): ?self
    {
        $res = $apiClient->get("channels/${channelId}");
        if ($res->getStatusCode() !== 200) {
            return null;
        }

        return new static(json_decode($res->getBody()->getContents(), true));
    }

    /**
     * Id of the channel
     *
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * Returns the type of the channel
     *
     * @return int
     * @see ChannelType
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * The id of the guild
     *
     * @return Snowflake|null
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * The position in the channel list
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * List of permission overwrites
     *
     * @return Overwrite[]
     */
    public function getPermissionOverwrites(): array
    {
        return $this->permission_overwrites;
    }

    /**
     * The name of the channel
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The topic of the channel
     *
     * @return ?string
     */
    public function getTopic(): ?string
    {
        return $this->topic;
    }

    /**
     * Whether this channel is marked as nsfw
     *
     * @return bool
     */
    public function isNsfw(): bool
    {
        return $this->nsfw;
    }

    /**
     * The id of the last message
     *
     * @return Snowflake|null
     */
    public function getLastMessageId(): ?Snowflake
    {
        return $this->last_message_id;
    }

    /**
     * Bitrate of the voice channel
     *
     * @return int
     */
    public function getBitrate(): int
    {
        return $this->bitrate;
    }

    /**
     * The limit of users for this voice channel
     *
     * @return int
     */
    public function getUserLimit(): int
    {
        return $this->user_limit;
    }

    /**
     * The rate limit for the channel
     *
     * @return int|mixed
     */
    public function getRateLimitPerUser(): int
    {
        return $this->rate_limit_per_user;
    }

    /**
     * If this is a dm, a list of all recipients
     *
     * @return User[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * The icon hash for this channel
     *
     * @return ?string
     */
    public function getIconHash(): ?string
    {
        return $this->icon;
    }

    /**
     * Returns the url to the channels icon.
     *
     * @param string $format If the image is not available in the given format, null is returned
     * @param int $size An int between 16 and 4096
     * @return string
     */
    public function getIconUrl(string $format = 'png', int $size = 1024): ?string
    {
        $baseUrl = Bot::BaseImageUrl;
        $channelId = $this->getId();
        $hash = $this->getIconHash();
        if (!$hash) {
            return null;
        }

        // make sure the size is within the allowed range
        $size = max(min($size, 4096), 16);

        // validate the image format
        if (!in_array($format, ['png', 'jpg', 'jpeg', 'webp'])) {
            return null;
        }

        return "${baseUrl}channel-icons/${channelId}/${hash}.${format}?size=${size}";
    }

    /**
     * Returns the id of the creator if it's a DM
     *
     * @return ?Snowflake
     */
    public function getOwnerId(): ?Snowflake
    {
        return $this->owner_id;
    }

    /**
     * Id of the application, if this is a bot-created channel
     *
     * @return ?Snowflake
     */
    public function getApplicationId(): ?Snowflake
    {
        return $this->application_id;
    }

    /**
     * The id of the parent category, if categorized
     *
     * @return ?Snowflake
     */
    public function getParentId(): ?Snowflake
    {
        return $this->parent_id;
    }

    /**
     * The timestamp of the last pinned message
     *
     * @return ?int
     */
    public function getLastPinTimestamp(): ?int
    {
        return $this->last_pin_timestamp;
    }

    /**
     * voice region id for the voice channel, automatic when set to null
     */
    public function getRtcRegion()
    {
        return $this->rtc_region;
    }

    /**
     * the camera video quality mode of the voice channel, 1 when not present
     */
    public function getVideoQualityMode()
    {
        return $this->video_quality_mode;
    }

    /**
     * an approximate count of messages in a thread, stops counting at 50
     */
    public function getMessageCount()
    {
        return $this->message_count;
    }

    /**
     * an approximate count of users in a thread, stops counting at 50
     */
    public function getMemberCount()
    {
        return $this->member_count;
    }

    /**
     * thread-specific fields not needed by other channels
     */
    public function getThreadMetadata(): ?ThreadMetadata
    {
        return $this->thread_metadata;
    }

    /**
     * thread member object for the current user, if they have joined the thread,
     * only included on certain API endpoints
     */
    public function getMember(): ?ThreadMember
    {
        return $this->member;
    }

    /**
     * default duration for newly created threads, in minutes, to automatically archive
     * the thread after recent activity, can be set to: 60, 1440, 4320, 10080
     */
    public function getDefaultAutoArchiveDuration()
    {
        return $this->default_auto_archive_duration;
    }

    /**
     * computed permissions for the invoking user in the channel, including overwrites,
     * only included when part of the resolved data received on a slash command interaction
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}