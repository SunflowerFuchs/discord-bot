<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

class VoiceState
{
    /**
     * the guild id this voice state is for
     */
    protected ?Snowflake $guild_id;
    /**
     * the channel id this user is connected to
     */
    protected ?Snowflake $channel_id;
    /**
     * the user id this voice state is for
     */
    protected Snowflake $user_id;
    /**
     * the guild member this voice state is for
     */
    protected ?GuildMember $member;
    /**
     * the session id for this voice state
     */
    protected string $session_id;
    /**
     * whether this user is deafened by the server
     */
    protected bool $deaf;
    /**
     * whether this user is muted by the server
     */
    protected bool $mute;
    /**
     * whether this user is locally deafened
     */
    protected bool $self_deaf;
    /**
     * whether this user is locally muted
     */
    protected bool $self_mute;
    /**
     * whether this user is streaming using "Go Live"
     */
    protected bool $self_stream;
    /**
     * whether this user's camera is enabled
     */
    protected bool $self_video;
    /**
     * whether this user is muted by the current user
     */
    protected bool $suppress;
    /**
     * the time at which the user requested to speak
     */
    protected int $request_to_speak_timestamp;

    public function __construct(array $data)
    {
        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->channel_id = !empty($data['channel_id']) ? new Snowflake($data['channel_id']) : null;
        $this->user_id = new Snowflake($data['user_id']);
        $this->member = !empty($data['member']) ? new GuildMember($data['member']) : null;
        $this->session_id = $data['session_id'];
        $this->deaf = $data['deaf'];
        $this->mute = $data['mute'];
        $this->self_deaf = $data['self_deaf'];
        $this->self_mute = $data['self_mute'];
        $this->self_stream = $data['self_stream'] ?? false;
        $this->self_video = $data['self_video'];
        $this->suppress = $data['suppress'];
        $this->request_to_speak_timestamp = !empty($data['request_to_speak_timestamp']) ? strtotime($data['request_to_speak_timestamp']) : 0;
    }

    /**
     * the guild id this voice state is for
     * @return ?Snowflake
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * the channel id this user is connected to
     * @return ?Snowflake
     */
    public function getChannelId(): ?Snowflake
    {
        return $this->channel_id;
    }

    /**
     * the user id this voice state is for
     * @return Snowflake
     */
    public function getUserId(): Snowflake
    {
        return $this->user_id;
    }

    /**
     * the guild member this voice state is for
     * @return ?GuildMember
     */
    public function getMember(): ?GuildMember
    {
        return $this->member;
    }

    /**
     * the session id for this voice state
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->session_id;
    }

    /**
     * whether this user is deafened by the server
     * @return bool
     */
    public function isDeafened(): bool
    {
        return $this->deaf;
    }

    /**
     * whether this user is muted by the server
     * @return bool
     */
    public function isMuted(): bool
    {
        return $this->mute;
    }

    /**
     * whether this user is locally deafened
     * @return bool
     */
    public function isSelfDeafened(): bool
    {
        return $this->self_deaf;
    }

    /**
     * whether this user is locally muted
     * @return bool
     */
    public function isSelfMuted(): bool
    {
        return $this->self_mute;
    }

    /**
     * whether this user is streaming using "Go Live"
     * @return bool
     */
    public function isSelfStreaming(): bool
    {
        return $this->self_stream;
    }

    /**
     * whether this user's camera is enabled
     * @return bool
     */
    public function isSelfVideoing(): bool
    {
        return $this->self_video;
    }

    /**
     * whether this user is muted by the current user
     * @return bool
     */
    public function isSuppressed(): bool
    {
        return $this->suppress;
    }

    /**
     * the time at which the user requested to speak
     * @return int
     */
    public function getRequestToSpeakTimestamp(): int
    {
        return $this->request_to_speak_timestamp;
    }


}