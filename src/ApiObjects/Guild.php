<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

use GuzzleHttp\Client;

class Guild
{
    /**
     * Guild id
     */
    protected Snowflake $id;
    /**
     * guild name (2-100 characters, excluding trailing and leading whitespace)
     */
    protected string $name;
    /**
     * icon hash
     */
    protected ?string $icon;
    /**
     * icon hash, returned when in the template object
     */
    protected ?string $icon_hash;
    /**
     * splash hash
     */
    protected ?string $splash;
    /**
     * discovery splash hash; only present for guilds with the "DISCOVERABLE" feature
     */
    protected ?string $discovery_splash;
    /**
     * id of owner
     */
    protected Snowflake $owner_id;
    /**
     * id of afk channel
     */
    protected ?Snowflake $afk_channel_id;
    /**
     * afk timeout in seconds
     */
    protected int $afk_timeout;
    /**
     * true if the server widget is enabled
     */
    protected bool $widget_enabled;
    /**
     * the channel id that the widget will generate an invite to, or null if set to no invite
     */
    protected ?Snowflake $widget_channel_id;
    /**
     * verification level required for the guild
     */
    protected int $verification_level;
    /**
     * default message notifications level
     */
    protected int $default_message_notifications;
    /**
     * explicit content filter level
     */
    protected int $explicit_content_filter;
    /**
     * roles in the guild
     * @var Role[]
     */
    protected array $roles;
    /**
     * custom guild emojis
     * @var Emoji[]
     */
    protected array $emojis;
    /**
     * enabled guild features
     * @var string[]
     */
    protected array $features;
    /**
     * required MFA level for the guild
     */
    protected int $mfa_level;
    /**
     * application id of the guild creator if it is bot-created
     */
    protected ?Snowflake $application_id;
    /**
     * the id of the channel where guild notices such as welcome messages and boost events are posted
     */
    protected ?Snowflake $system_channel_id;
    /**
     * system channel flags
     */
    protected int $system_channel_flags;
    /**
     * the id of the channel where Community guilds can display rules and/or guidelines
     */
    protected ?Snowflake $rules_channel_id;
    /**
     * when this guild was joined at
     *
     * only sent within the GUILD_CREATE event
     */
    protected int $joined_at;
    /**
     * true if this is considered a large guild
     *
     * only sent within the GUILD_CREATE event
     */
    protected bool $large;
    /**
     * true if this guild is unavailable due to an outage
     *
     * only sent within the GUILD_CREATE event
     */
    protected bool $unavailable;
    /**
     * total number of members in this guild
     *
     * only sent within the GUILD_CREATE event
     */
    protected int $member_count;
    /**
     * states of members currently in voice channels; lacks the guild_id key
     *
     * only sent within the GUILD_CREATE event
     * @var VoiceState[]
     */
    protected array $voice_states;
    /**
     * users in the guild
     *
     * only sent within the GUILD_CREATE event
     * @var GuildMember[]
     */
    protected array $members;
    /**
     * channels in the guild
     *
     * only sent within the GUILD_CREATE event
     * @var Channel[]
     */
    protected array $channels;
    /**
     * all active threads in the guild that current user has permission to view
     *
     * only sent within the GUILD_CREATE event
     * @var Channel[]
     */
    protected array $threads;
    /**
     * presences of the members in the guild, will only include non-offline members if the size is greater than large threshold
     *
     * only sent within the GUILD_CREATE event
     * @var Presence[]
     */
    protected array $presences;
    /**
     * the maximum number of presences for the guild (null is always returned, apart from the largest of guilds)
     */
    protected int $max_presences;
    /**
     * the maximum number of members for the guild
     */
    protected int $max_members;
    /**
     * the vanity url code for the guild
     */
    protected ?string $vanity_url_code;
    /**
     * the description of a guild
     */
    protected string $description;
    /**
     * banner hash
     */
    protected ?string $banner;
    /**
     * premium tier (Server Boost level)
     */
    protected int $premium_tier;
    /**
     * the number of boosts this guild currently has
     */
    protected int $premium_subscription_count;
    /**
     * the preferred locale of a Community guild; used in server discovery and notices from Discord, and sent in interactions; defaults to "en-US"
     */
    protected string $preferred_locale;
    /**
     * the id of the channel where admins and moderators of Community guilds receive notices from Discord
     */
    protected ?Snowflake $public_updates_channel_id;
    /**
     * the maximum amount of users in a video channel
     */
    protected int $max_video_channel_users;
    /**
     * approximate number of members in this guild, returned from the GET /guilds/<id> endpoint when with_counts is true
     */
    protected int $approximate_member_count;
    /**
     * approximate number of non-offline members in this guild, returned from the GET /guilds/<id> endpoint when with_counts is true
     */
    protected int $approximate_presence_count;
    /**
     * the welcome screen of a Community guild, shown to new members, returned in an Invite's guild object
     */
    protected ?WelcomeScreen $welcome_screen;
    /**
     * guild NSFW level
     */
    protected int $nsfw_level;
    /**
     * Stage instances in the guild
     *
     * only sent within the GUILD_CREATE event
     * @var StageInstance[]
     */
    protected array $stage_instances;
    /**
     * custom guild stickers
     * @var Sticker[]
     */
    protected array $stickers;
    /**
     * the scheduled events in the guild
     *
     * only sent within the GUILD_CREATE event
     * @var GuildScheduledEvent[]
     */
    protected array $guild_scheduled_events;
    /**
     * whether the guild has the boost progress bar enabled
     */
    protected bool $premium_progress_bar_enabled;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->name = $data['name'];
        $this->icon = $data['icon'] ?? null;
        $this->icon_hash = $data['icon_hash'] ?? null;
        $this->splash = $data['splash'] ?? null;
        $this->discovery_splash = $data['discovery_splash'] ?? null;
        $this->owner_id = new Snowflake($data['owner_id']);
        $this->afk_channel_id = !empty($data['afk_channel_id']) ? new Snowflake($data['afk_channel_id']) : null;
        $this->afk_timeout = $data['afk_timeout'];
        $this->widget_enabled = $data['widget_enabled'] ?? false;
        $this->widget_channel_id = !empty($data['widget_channel_id']) ? new Snowflake($data['widget_channel_id']) : null;
        $this->verification_level = $data['verification_level'];
        $this->default_message_notifications = $data['default_message_notifications'];
        $this->explicit_content_filter = $data['explicit_content_filter'];
        $this->features = $data['features'];
        $this->mfa_level = $data['mfa_level'];
        $this->application_id = !empty($data['application_id']) ? new Snowflake($data['application_id']) : null;
        $this->system_channel_id = !empty($data['system_channel_id']) ? new Snowflake($data['system_channel_id']) : null;
        $this->system_channel_flags = $data['system_channel_flags'];
        $this->rules_channel_id = !empty($data['rules_channel_id']) ? new Snowflake($data['rules_channel_id']) : null;
        $this->joined_at = !empty($data['joined_at']) ? strtotime($data['joined_at']) : 0;
        $this->large = $data['large'] ?? false;
        $this->unavailable = $data['unavailable'] ?? false;
        $this->member_count = $data['member_count'] ?? 0;
        $this->max_presences = $data['max_presences'] ?? 0;
        $this->max_members = $data['max_members'];
        $this->vanity_url_code = $data['vanity_url_code'] ?? null;
        $this->description = $data['description'] ?? '';
        $this->banner = $data['banner'] ?? null;
        $this->premium_tier = $data['premium_tier'];
        $this->premium_subscription_count = $data['premium_subscription_count'] ?? 0;
        $this->preferred_locale = $data['preferred_locale'];
        $this->public_updates_channel_id = !empty($data['public_updates_channel_id']) ? new Snowflake($data['public_updates_channel_id']) : null;
        $this->max_video_channel_users = $data['max_video_channel_users'] ?? 0;
        $this->approximate_member_count = $data['approximate_member_count'] ?? 0;
        $this->approximate_presence_count = $data['approximate_presence_count'] ?? 0;
        $this->welcome_screen = !empty($data['welcome_screen']) ? new WelcomeScreen($data['welcome_screen']) : null;
        $this->nsfw_level = $data['nsfw_level'];
        $this->premium_progress_bar_enabled = $data['premium_progress_bar_enabled'];

        $this->roles = array_map(fn(array $roleData) => new Role($roleData),
            $data['roles']);
        $this->emojis = array_map(fn(array $emojiData) => new Emoji($emojiData),
            $data['emojis']);
        $this->voice_states = array_map(fn(array $voiceStateData) => new VoiceState($voiceStateData),
            $data['voice_states'] ?? []);
        $this->members = array_map(fn(array $memberData) => new GuildMember($memberData),
            $data['members'] ?? []);
        $this->channels = array_map(fn(array $channelData) => new Channel($channelData),
            $data['channels'] ?? []);
        $this->threads = array_map(fn(array $threadData) => new Channel($threadData),
            $data['threads'] ?? []);
        $this->presences = array_map(fn(array $presenceData) => new Presence($presenceData),
            $data['presences'] ?? []);
        $this->stage_instances = array_map(fn(array $stageInstanceData) => new StageInstance($stageInstanceData),
            $data['stage_instances'] ?? []);
        $this->stickers = array_map(fn(array $stickerData) => new Sticker($stickerData),
            $data['stickers'] ?? []);
        $this->guild_scheduled_events = array_map(fn(array $eventData) => new GuildScheduledEvent($eventData),
            $data['guild_scheduled_events'] ?? []);
    }

    public static function loadById(Client $apiClient, string $guildId, bool $withCounts = false): ?self
    {
        $withCountsStr = $withCounts ? 'true' : 'false';
        $res = $apiClient->get("guilds/${guildId}?with_counts=${withCountsStr}");
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * Guild id
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * guild name (2-100 characters, excluding trailing and leading whitespace)
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * icon hash
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * icon hash, returned when in the template object
     * @return string|null
     */
    public function getIconHash(): ?string
    {
        return $this->icon_hash;
    }

    /**
     * splash hash
     * @return string|null
     */
    public function getSplash(): ?string
    {
        return $this->splash;
    }

    /**
     * discovery splash hash; only present for guilds with the "DISCOVERABLE" feature
     * @return string|null
     */
    public function getDiscoverySplash(): ?string
    {
        return $this->discovery_splash;
    }

    /**
     * id of owner
     * @return Snowflake
     */
    public function getOwnerId(): Snowflake
    {
        return $this->owner_id;
    }

    /**
     * id of afk channel
     * @return Snowflake|null
     */
    public function getAfkChannelId(): ?Snowflake
    {
        return $this->afk_channel_id;
    }

    /**
     * afk timeout in seconds
     * @return int
     */
    public function getAfkTimeout(): int
    {
        return $this->afk_timeout;
    }

    /**
     * true if the server widget is enabled
     * @return bool
     */
    public function isWidgetEnabled(): bool
    {
        return $this->widget_enabled;
    }

    /**
     * the channel id that the widget will generate an invite to, or null if set to no invite
     * @return Snowflake|null
     */
    public function getWidgetChannelId(): ?Snowflake
    {
        return $this->widget_channel_id;
    }

    /**
     * verification level required for the guild
     * @return int
     */
    public function getVerificationLevel(): int
    {
        return $this->verification_level;
    }

    /**
     * default message notifications level
     * @return int
     */
    public function getDefaultMessageNotifications(): int
    {
        return $this->default_message_notifications;
    }

    /**
     * explicit content filter level
     * @return int
     */
    public function getExplicitContentFilter(): int
    {
        return $this->explicit_content_filter;
    }

    /**
     * roles in the guild
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * custom guild emojis
     * @return Emoji[]
     */
    public function getEmojis(): array
    {
        return $this->emojis;
    }

    /**
     * enabled guild features
     * @return string[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * required MFA level for the guild
     * @return int
     */
    public function getMfaLevel(): int
    {
        return $this->mfa_level;
    }

    /**
     * application id of the guild creator if it is bot-created
     * @return Snowflake|null
     */
    public function getApplicationId(): ?Snowflake
    {
        return $this->application_id;
    }

    /**
     * the id of the channel where guild notices such as welcome messages and boost events are posted
     * @return Snowflake|null
     */
    public function getSystemChannelId(): ?Snowflake
    {
        return $this->system_channel_id;
    }

    /**
     * system channel flags
     * @return int
     */
    public function getSystemChannelFlags(): int
    {
        return $this->system_channel_flags;
    }

    /**
     * the id of the channel where Community guilds can display rules and/or guidelines
     * @return Snowflake|null
     */
    public function getRulesChannelId(): ?Snowflake
    {
        return $this->rules_channel_id;
    }

    /**
     * when this guild was joined at
     * @return int
     */
    public function getJoinedAt(): int
    {
        return $this->joined_at;
    }

    /**
     * true if this is considered a large guild
     * @return bool
     */
    public function isLarge(): bool
    {
        return $this->large;
    }

    /**
     * true if this guild is unavailable due to an outage
     * @return bool
     */
    public function isUnavailable(): bool
    {
        return $this->unavailable;
    }

    /**
     * total number of members in this guild
     * @return int
     */
    public function getMemberCount(): int
    {
        return $this->member_count;
    }

    /**
     * states of members currently in voice channels; lacks the guild_id key
     * @return VoiceState[]
     */
    public function getVoiceStates(): array
    {
        return $this->voice_states;
    }

    /**
     * users in the guild
     * @return GuildMember[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * channels in the guild
     * @return Channel[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * all active threads in the guild that current user has permission to view
     * @return Channel[]
     */
    public function getThreads(): array
    {
        return $this->threads;
    }

    /**
     * presences of the members in the guild, will only include non-offline members if the size is greater than large threshold
     * @return Presence[]
     */
    public function getPresences(): array
    {
        return $this->presences;
    }

    /**
     * the maximum number of presences for the guild (null is always returned, apart from the largest of guilds)
     * @return int
     */
    public function getMaxPresences(): int
    {
        return $this->max_presences;
    }

    /**
     * the maximum number of members for the guild
     * @return int
     */
    public function getMaxMembers(): int
    {
        return $this->max_members;
    }

    /**
     * the vanity url code for the guild
     * @return ?string
     */
    public function getVanityUrlCode(): ?string
    {
        return $this->vanity_url_code;
    }

    /**
     * the description of a guild
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * banner hash
     * @return ?string
     */
    public function getBanner(): ?string
    {
        return $this->banner;
    }

    /**
     * premium tier (Server Boost level)
     * @return int
     */
    public function getPremiumTier(): int
    {
        return $this->premium_tier;
    }

    /**
     * the number of boosts this guild currently has
     * @return int
     */
    public function getPremiumSubscriptionCount(): int
    {
        return $this->premium_subscription_count;
    }

    /**
     * the preferred locale of a Community guild; used in server discovery and notices from Discord, and sent in interactions; defaults to "en-US"
     * @return string
     */
    public function getPreferredLocale(): string
    {
        return $this->preferred_locale;
    }

    /**
     * the id of the channel where admins and moderators of Community guilds receive notices from Discord
     * @return Snowflake|null
     */
    public function getPublicUpdatesChannelId(): ?Snowflake
    {
        return $this->public_updates_channel_id;
    }

    /**
     * the maximum amount of users in a video channel
     * @return int
     */
    public function getMaxVideoChannelUsers(): int
    {
        return $this->max_video_channel_users;
    }

    /**
     * approximate number of members in this guild, returned from the GET /guilds/<id> endpoint when with_counts is true
     * @return int
     */
    public function getApproximateMemberCount(): int
    {
        return $this->approximate_member_count;
    }

    /**
     * approximate number of non-offline members in this guild, returned from the GET /guilds/<id> endpoint when with_counts is true
     * @return int
     */
    public function getApproximatePresenceCount(): int
    {
        return $this->approximate_presence_count;
    }

    /**
     * the welcome screen of a Community guild, shown to new members, returned in an Invite's guild object
     * @return WelcomeScreen|null
     */
    public function getWelcomeScreen(): ?WelcomeScreen
    {
        return $this->welcome_screen;
    }

    /**
     * guild NSFW level
     * @return int
     */
    public function getNsfwLevel(): int
    {
        return $this->nsfw_level;
    }

    /**
     * Stage instances in the guild
     * @return StageInstance[]
     */
    public function getStageInstances(): array
    {
        return $this->stage_instances;
    }

    /**
     * custom guild stickers
     * @return Sticker[]
     */
    public function getStickers(): array
    {
        return $this->stickers;
    }

    /**
     * the scheduled events in the guild
     * @return GuildScheduledEvent[]
     */
    public function getGuildScheduledEvents(): array
    {
        return $this->guild_scheduled_events;
    }

    /**
     * whether the guild has the boost progress bar enabled
     * @return bool
     */
    public function isPremiumProgressBarEnabled(): bool
    {
        return $this->premium_progress_bar_enabled;
    }


}