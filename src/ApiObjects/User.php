<?php


namespace SunflowerFuchs\DiscordBot\ApiObjects;


use SunflowerFuchs\DiscordBot\Bot;

class User
{
    const PREMIUM_NONE = 0;
    const PREMIUM_CLASSIC = 1;
    const PREMIUM_NITRO = 2;

    protected Snowflake $id;
    protected string $username;
    protected string $discriminator;
    protected ?string $avatar;
    protected bool $bot;
    protected bool $system;
    protected bool $mfa;
    protected string $locale;
    protected bool $verified;
    protected ?string $email;
    protected int $flags;
    protected int $premium_type;
    protected int $public_flags;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->username = $data['username'];
        $this->discriminator = $data['discriminator'];
        $this->avatar = $data['avatar'];

        $this->bot = $data['bot'] ?? false;
        $this->system = $data['system'] ?? false;
        $this->mfa = $data['mfa_enabled'] ?? false;
        $this->locale = $data['locale'] ?? 'en_US';
        $this->verified = $data['verified'] ?? true;
        $this->email = $data['email'] ?? null;
        $this->flags = $data['flags'] ?? 0;
        $this->premium_type = $data['premium_type'] ?? 0;
        $this->public_flags = $data['public_flags'] ?? 0;
    }

    public static function loadById(string $userId): ?self
    {
        $res = Bot::getInstance()->getApiClient()->get("users/${userId}");
        if ($res->getStatusCode() === 200) {
            return new static(json_decode($res->getBody()->getContents(), true));
        }

        return null;
    }

    /**
     * Get the users username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get the users discriminator
     *
     * The discriminator is the number behind the username, e.g. JohnDoe#1234
     *
     * @return string
     */
    public function getDiscriminator(): string
    {
        return $this->discriminator;
    }

    /**
     * Shortcut to get a sharable username
     *
     * Returns the username in the format <Username>#<Discriminator>
     *
     * @return string
     */
    public function getShareableUsername(): string
    {
        $username = $this->getUsername();
        $discriminator = $this->getDiscriminator();
        return "${username}#${discriminator}";
    }

    /**
     * Returns the id of the user as a snowflake object
     *
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * Returns the hash of the users avatar, or null if no custom avatar is set
     *
     * @return string|null
     */
    public function getAvatarHash(): ?string
    {
        return $this->avatar;
    }

    /**
     * Returns the url to the users avatar image.
     *
     * @param string $format If the image is not available in the given format, null is returned
     * @param int $size An int between 16 and 4096. If the user has no custom avatar, the size parameter is ignored
     * @return string
     */
    public function getAvatarUrl(string $format = 'png', int $size = 1024): ?string
    {
        // make sure the size is within the allowed range
        $size = max(min($size, 4096), 16);

        $baseImageUrl = Bot::BaseImageUrl;
        $hash = $this->getAvatarHash();
        if ($hash) {
            if (!in_array($format, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                return null;
            }

            if ($format === 'gif' && substr($hash, 0, 2) !== 'a_') {
                return null;
            }

            $userId = $this->getId();
            return "${baseImageUrl}/avatars/${userId}/${hash}.${format}?size=${size}";
        }

        // The default avatar endpoint only supports png
        if ($format !== 'png') {
            return null;
        }

        $discrimMod = intval($this->getDiscriminator()) % 5;
        return "${baseImageUrl}/embed/avatars/${discrimMod}.${format}";
    }

    /**
     * Returns whether the user is a bot user
     *
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->bot;
    }

    /**
     * Returns whether the user is a discord system account
     *
     * @return bool
     */
    public function isSystemUser(): bool
    {
        return $this->system;
    }

    /**
     * Returns whether the user has multifactor authentication enabled
     *
     * @return bool
     */
    protected function hasMultifactorEnabled(): bool
    {
        return $this->mfa;
    }

    /**
     * Returns the locale of the user
     *
     * If unknown, defaults to en_US
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Returns whether the user has confirmed their email address, if known
     *
     * Defaults to true if unknown
     *
     * Requires a different oauth2 scope
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * Returns the users email address, if available
     *
     * Requires a different oauth2 scope
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get the type of the nitro subscription
     * @return int
     * @see PREMIUM_NONE
     * @see PREMIUM_NITRO
     * @see PREMIUM_CLASSIC
     */
    public function getPremiumType(): int
    {
        return $this->premium_type;
    }

    /**
     * Boolean shortcut to check users nitro status
     *
     * If you need to differentiate between regular and classic nitro, see $this->{@see getPremiumType()}
     *
     * @return bool
     */
    public function hasNitro(): bool
    {
        return $this->getPremiumType() !== 0;
    }

    // TODO: make the flags easy to deal with

    /**
     * Returns the flags on an user
     *
     * @return int
     */
    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * Returns the public flags on an user
     *
     * @return int
     */
    public function getPublicFlags(): int
    {
        return $this->public_flags;
    }
}