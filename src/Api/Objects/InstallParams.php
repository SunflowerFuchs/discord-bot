<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Api\Constants\OAuthScopes;
use SunflowerFuchs\DiscordBot\Api\Constants\Permissions;

class InstallParams
{
    /**
     * the scopes to add the application to the server with
     * @see OAuthScopes
     * @var string[]
     */
    protected array $scopes;
    /**
     * the permissions to request for the bot role
     * @see Permissions
     */
    protected string $permissions;

    public function __construct(array $data)
    {
        $this->scopes = $data['scopes'];
        $this->permissions = $data['permissions'];
    }

    /**
     * the scopes to add the application to the server with
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * the permissions to request for the bot role
     * @return string
     */
    public function getPermissions(): string
    {
        return $this->permissions;
    }


}