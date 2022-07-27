<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use Medoo\Medoo;
use PDO;
use SunflowerFuchs\DiscordBot\Api\Objects\Guild;
use SunflowerFuchs\DiscordBot\Api\Objects\GuildMember;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;
use SunflowerFuchs\DiscordBot\Bot;

/**
 * @TODO Improve permission system
 */
class PermissionManager
{
    protected const FILE_NAME = 'permissions.sqlite';
    protected const TABLE_MEMBERS = 'member_permissions';
    protected const TABLE_ROLES = 'role_permissions';
    protected const COL_PERMISSION = 'permission';
    protected const COL_GUILD_ID = 'guild_id';
    protected const COL_USER_ID = 'user_id';
    protected const COL_ROLE_ID = 'role_id';

    protected Bot $bot;
    protected Medoo $db;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
        $this->db = new Medoo([
            'type' => 'sqlite',
            'database' => $this->bot->getDataDir() . self::FILE_NAME,
            'error' => PDO::ERRMODE_SILENT
        ]);

        $this->initializeDatabase();
    }

    protected function permitMember(Snowflake $guildId, GuildMember $guildMember, string $permission): bool
    {
        if ($guildMember->getUser() === null) {
            return false;
        }

        $this->db->insert(self::TABLE_MEMBERS, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_USER_ID => $guildMember->getUser()->getId()->toInt(),
            self::COL_PERMISSION => $permission
        ]);

        return empty($this->db->error);
    }

    protected function denyMember(Snowflake $guildId, GuildMember $guildMember, string $permission): bool
    {
        if ($guildMember->getUser() === null) {
            return false;
        }

        $this->db->delete(self::TABLE_MEMBERS, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_USER_ID => $guildMember->getUser()->getId()->toInt(),
            self::COL_PERMISSION => $permission
        ]);

        return empty($this->db->error);
    }

    protected function permitRole(Snowflake $guildId, Snowflake $roleId, string $permission): bool
    {
        $this->db->insert(self::TABLE_ROLES, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_ROLE_ID => $roleId->toInt(),
            self::COL_PERMISSION => $permission
        ]);

        return empty($this->db->error);
    }

    protected function denyRole(Snowflake $guildId, Snowflake $roleId, string $permission): bool
    {
        $this->db->delete(self::TABLE_ROLES, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_ROLE_ID => $roleId->toInt(),
            self::COL_PERMISSION => $permission
        ]);

        return empty($this->db->error);
    }

    /**
     * @noinspection PhpNonStrictObjectEqualityInspection
     */
    protected function memberHasPermission(Snowflake $guildId, GuildMember $guildMember, string $permission): bool
    {
        if ($guildMember->getUser() === null) {
            return false;
        }

        // The owner always has ALL permissions
        $guild = Guild::loadById($this->bot->getApiClient(), $guildId);
        if ($guild->getOwnerId() == $guildMember->getUser()->getId()) {
            return true;
        }

        // Check if the user has been given the permission directly
        $hasPermission = $this->db->count(self::TABLE_MEMBERS, null, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_USER_ID => $guildMember->getUser()->getId()->toInt(),
            self::COL_PERMISSION => $permission
        ]);
        if ($hasPermission === 1) {
            return true;
        }

        // If the user wasn't found to have the permission assigned, check their roles too
        $roleIds = $guildMember->getRoles();
        if (!empty($roleIds)) {
            return $this->db->has(self::TABLE_ROLES, [
                self::COL_GUILD_ID => $guildId->toInt(),
                self::COL_ROLE_ID => array_map(fn(Snowflake $roleId) => $roleId->toInt(), $roleIds),
                self::COL_PERMISSION => $permission
            ]);
        }

        return false;
    }

    /**
     * @param Snowflake $guildId
     * @param string $permission
     * @return Snowflake[]
     * @noinspection PhpParamsInspection Medoo has weird params here
     */
    protected function listMembersWithPermission(Snowflake $guildId, string $permission): array
    {
        $userIds = $this->db->select(self::TABLE_MEMBERS, self::COL_USER_ID, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_PERMISSION => $permission
        ]);

        return array_map(fn(int $userId) => new Snowflake("$userId"), $userIds ?? []);
    }

    /**
     * @param Snowflake $guildId
     * @param string $permission
     * @return Snowflake[]
     * @noinspection PhpParamsInspection Medoo has weird params here
     */
    protected function listRolesWithPermission(Snowflake $guildId, string $permission): array
    {
        $roleIds = $this->db->select(self::TABLE_ROLES, self::COL_ROLE_ID, [
            self::COL_GUILD_ID => $guildId->toInt(),
            self::COL_PERMISSION => $permission
        ]);

        return array_map(fn(int $roleId) => new Snowflake("$roleId"), $roleIds ?? []);
    }

    protected function initializeDatabase(): void
    {
        $this->db->create(self::TABLE_MEMBERS, [
            self::COL_GUILD_ID => [
                'INT',
                'NOT NULL',
            ],
            self::COL_USER_ID => [
                'INT',
                'NOT NULL',
            ],
            self::COL_PERMISSION => [
                'TEXT',
                'NOT NULL',
            ],
            'PRIMARY KEY (' . implode(',', [self::COL_GUILD_ID, self::COL_USER_ID, self::COL_PERMISSION]) . ')'
        ]);
        if (!empty($this->db->error)) {
            $this->bot->getLogger()->critical($this->db->error);
            $this->bot->stop();
            return;
        }

        $this->db->create(self::TABLE_ROLES, [
            self::COL_GUILD_ID => [
                'INT',
                'NOT NULL',
            ],
            self::COL_ROLE_ID => [
                'INT',
                'NOT NULL',
            ],
            self::COL_PERMISSION => [
                'TEXT',
                'NOT NULL',
            ],
            'PRIMARY KEY (' . implode(',', [self::COL_GUILD_ID, self::COL_ROLE_ID, self::COL_PERMISSION]) . ')'
        ]);

        if (!empty($this->db->error)) {
            $this->bot->getLogger()->critical($this->db->error);
            $this->bot->stop();
        }
    }
}