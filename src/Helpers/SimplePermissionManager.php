<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use SunflowerFuchs\DiscordBot\Api\Objects\GuildMember;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;

class SimplePermissionManager extends PermissionManager
{
    protected const PERMISSION_ADMIN = 'guild_admin';

    public function isAdmin(Snowflake $guildId, GuildMember $guildMember): bool
    {
        return $this->memberHasPermission($guildId, $guildMember, self::PERMISSION_ADMIN);
    }

    public function giveAdmin(Snowflake $guildId, GuildMember $guildMember): bool
    {
        return $this->permitMember($guildId, $guildMember, self::PERMISSION_ADMIN);
    }

    public function removeAdmin(Snowflake $guildId, GuildMember $guildMember): bool
    {
        return $this->denyMember($guildId, $guildMember, self::PERMISSION_ADMIN);
    }

    public function giveRoleAdmin(Snowflake $guildId, Snowflake $roleId): bool
    {
        return $this->permitRole($guildId, $roleId, self::PERMISSION_ADMIN);
    }

    public function removeRoleAdmin(Snowflake $guildId, Snowflake $roleId): bool
    {
        return $this->denyRole($guildId, $roleId, self::PERMISSION_ADMIN);
    }

    /**
     * @param Snowflake $guildId
     * @return Snowflake[]
     */
    public function listAdmins(Snowflake $guildId): array
    {
        return $this->listMembersWithPermission($guildId, self::PERMISSION_ADMIN);
    }

    /**
     * @param Snowflake $guildId
     * @return Snowflake[]
     */
    public function listAdminRoles(Snowflake $guildId): array
    {
        return $this->listRolesWithPermission($guildId, self::PERMISSION_ADMIN);
    }
}