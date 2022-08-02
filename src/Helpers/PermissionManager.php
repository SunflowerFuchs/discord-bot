<?php

namespace SunflowerFuchs\DiscordBot\Helpers;

use Exception;
use PDO;
use Pecee\Pixie\Connection;
use Pecee\Pixie\Exceptions\DuplicateEntryException;
use Pecee\Pixie\QueryBuilder\QueryBuilderHandler;
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
    protected QueryBuilderHandler $db;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
        $this->db = (new Connection('sqlite', [
            'driver' => 'sqlite',
            'database' => $this->bot->getDataDir() . self::FILE_NAME,
        ]))->getQueryBuilder();

        $this->initializeDatabase();
    }

    protected function permitMember(Snowflake $guildId, GuildMember $guildMember, string $permission): bool
    {
        if ($guildMember->getUser() === null) {
            return false;
        }

        try {
            $this->db
                ->table(self::TABLE_MEMBERS)
                ->insert([
                    self::COL_GUILD_ID => $guildId->toInt(),
                    self::COL_USER_ID => $guildMember->getUser()->getId()->toInt(),
                    self::COL_PERMISSION => $permission
                ]);
        } catch (DuplicateEntryException $e) {
            // ignore duplicate call
            return true;
        } catch (Exception $e) {
            $this->bot->getLogger()->error('Unexpected exception', [$e]);
            return false;
        }

        return true;
    }

    protected function denyMember(Snowflake $guildId, GuildMember $guildMember, string $permission): bool
    {
        if ($guildMember->getUser() === null) {
            return false;
        }

        try {
            $this->db
                ->table(self::TABLE_MEMBERS)
                ->where(self::COL_GUILD_ID, '=', $guildId->toInt())
                ->where(self::COL_USER_ID, '=', $guildMember->getUser()->getId()->toInt())
                ->where(self::COL_PERMISSION, '=', $permission)
                ->delete();
        } catch (Exception $e) {
            $this->bot->getLogger()->error('Unexpected exception', [$e]);
            return false;
        }

        return true;
    }

    protected function permitRole(Snowflake $guildId, Snowflake $roleId, string $permission): bool
    {
        try {
            $this->db
                ->table(self::TABLE_ROLES)
                ->insert([
                    self::COL_GUILD_ID => $guildId->toInt(),
                    self::COL_ROLE_ID => $roleId->toInt(),
                    self::COL_PERMISSION => $permission
                ]);
        } catch (DuplicateEntryException $e) {
            // ignore duplicate call
            return true;
        } catch (Exception $e) {
            $this->bot->getLogger()->error('Unexpected exception', [$e]);
            return false;
        }

        return true;
    }

    protected function denyRole(Snowflake $guildId, Snowflake $roleId, string $permission): bool
    {
        try {
            $this->db
                ->table(self::TABLE_ROLES)
                ->where(self::COL_GUILD_ID, '=', $guildId->toInt())
                ->where(self::COL_ROLE_ID, '=', $roleId->toInt())
                ->where(self::COL_PERMISSION, '=', $permission)
                ->delete();
        } catch (Exception $e) {
            $this->bot->getLogger()->error('Unexpected exception', [$e]);
            return false;
        }

        return true;
    }

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
            return $this->db
                    ->table(self::TABLE_ROLES)
                    ->where(self::COL_GUILD_ID, '=', $guildId->toInt())
                    ->where(self::COL_PERMISSION, '=', $permission)
                    ->whereIn(self::COL_ROLE_ID, array_map(fn(Snowflake $roleId) => $roleId->toInt(), $roleIds))
                    ->count() >= 1;
        }

        return false;
    }

    /**
     * @param Snowflake $guildId
     * @param string $permission
     * @return Snowflake[]
     */
    protected function listMembersWithPermission(Snowflake $guildId, string $permission): array
    {
        $userIds = $this->db
            ->table(self::TABLE_MEMBERS)
            ->select(self::COL_USER_ID)
            ->where(self::COL_GUILD_ID, '=', $guildId->toInt())
            ->where(self::COL_PERMISSION, '=', $permission)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();

        return array_map(fn(int $userId) => new Snowflake("$userId"), $userIds ?? []);
    }

    /**
     * @param Snowflake $guildId
     * @param string $permission
     * @return Snowflake[]
     */
    protected function listRolesWithPermission(Snowflake $guildId, string $permission): array
    {
        $roleIds = $this->db
            ->table(self::TABLE_ROLES)
            ->select(self::COL_ROLE_ID)
            ->where(self::COL_GUILD_ID, '=', $guildId->toInt())
            ->where(self::COL_PERMISSION, '=', $permission)
            ->get();

        return array_map(fn(int $roleId) => new Snowflake("$roleId"), $roleIds);
    }

    protected function initializeDatabase(): void
    {
        // Both tables share the same last columns, only the name and first column are different
        $template = <<<'SQL'
CREATE TABLE IF NOT EXISTS
%1$s (
    %2$s INT NOT NULL,
    %3$s INT NOT NULL,
    %4$s TEXT NOT NULL,
    PRIMARY KEY (%2$s, %3$s, %4$s)
)
SQL;

        try {
            $tables = [[self::TABLE_MEMBERS, self::COL_USER_ID], [self::TABLE_ROLES, self::COL_ROLE_ID]];
            foreach ($tables as [$table, $col]) {
                $this->db->pdo()->exec(sprintf($template, $table, $col, self::COL_GUILD_ID, self::COL_PERMISSION));
            }
        } catch (Exception $e) {
            $this->bot->getLogger()->critical('Unexpected exception', [$e]);
            $this->bot->stop();
            return;
        }
    }
}