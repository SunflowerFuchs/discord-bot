<?php

namespace SunflowerFuchs\DiscordBot\Plugins;

use SunflowerFuchs\DiscordBot\Api\Objects\Message;
use SunflowerFuchs\DiscordBot\Api\Objects\Snowflake;

class AdminPermissionPlugin extends BasePlugin
{

    public function init()
    {
        $this->getBot()->registerCommand('op', [$this, 'op']);
        $this->getBot()->registerCommand('deop', [$this, 'deop']);
        $this->getBot()->registerCommand('listAdmins', [$this, 'listAdmins']);
    }

    public function op(Message $message): bool
    {
        $permissionManager = $this->getBot()->getPermissionManager();
        if (!$permissionManager->isAdmin($message->getGuildId(), $message->getMember())) {
            return true;
        }

        $roles = $message->getMentionRoles();
        $users = $message->getMentions();
        foreach ($roles as $roleId) {
            $permissionManager->giveRoleAdmin($message->getGuildId(), $roleId);
        }
        foreach ($users as $userMention) {
            $permissionManager->giveAdmin($message->getGuildId(), $userMention->getMember());
        }

        if (empty($roles) && empty($users)) {
            $prefix = $this->getBot()->getPrefix();
            $this->sendMessage(
                "No users/roles given. Syntax: ${prefix}op @role|@user [@role|@user] ...",
                $message->getChannelId()
            );
            return true;
        }

        $this->sendMessage('Permissions updated.', $message->getChannelId());
        return true;
    }

    public function deop(Message $message): bool
    {
        $permissionManager = $this->getBot()->getPermissionManager();
        if (!$permissionManager->isAdmin($message->getGuildId(), $message->getMember())) {
            return true;
        }

        $roles = $message->getMentionRoles();
        $users = $message->getMentions();
        foreach ($roles as $roleId) {
            $permissionManager->removeRoleAdmin($message->getGuildId(), $roleId);
        }
        foreach ($users as $userMention) {
            $permissionManager->removeAdmin($message->getGuildId(), $userMention->getMember());
        }

        if (empty($roles) && empty($users)) {
            $prefix = $this->getBot()->getPrefix();
            $this->sendMessage(
                "No users/roles given. Syntax: ${prefix}deop @role|@user [@role|@user] ...",
                $message->getChannelId()
            );
            return true;
        }

        $this->sendMessage('Permissions updated.', $message->getChannelId());
        return true;
    }

    public function listAdmins(Message $message): bool
    {
        $permissionManager = $this->getBot()->getPermissionManager();
        if (!$permissionManager->isAdmin($message->getGuildId(), $message->getMember())) {
            return true;
        }

        $adminMentions = array_map(
            fn(Snowflake $adminId) => "<@${adminId}>",
            $permissionManager->listAdmins($message->getGuildId())
        );
        $roleMentions = array_map(
            fn(Snowflake $roleId) => "<@&${roleId}>",
            $permissionManager->listAdminRoles($message->getGuildId())
        );
        if (empty($adminMentions) && empty($roleMentions)) {
            $this->sendMessage('Currently no admins defined.', $message->getChannelId());
            return true;
        }

        $this->sendMessage(
            "Current admins:\n" . implode("\n", $adminMentions)
            . "\n\nCurrent admin roles:\n" . implode("\n", $roleMentions),
            $message->getChannelId());
        return true;
    }
}