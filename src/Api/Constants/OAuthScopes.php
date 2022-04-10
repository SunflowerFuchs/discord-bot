<?php
declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class OAuthScopes
{
    /**
     * allows your app to fetch data from a user's "Now Playing/Recently Played" list - requires Discord approval
     */
    const ACTIVITIES_READ = 'activities.read';
    /**
     * allows your app to update a user's activity - requires Discord approval (NOT REQUIRED FOR GAMESDK ACTIVITY MANAGER)
     */
    const ACTIVITIES_WRITE = 'activities.write';
    /**
     * allows your app to read build data for a user's applications
     */
    const APPLICATIONS_BUILDS_READ = 'applications.builds.read';
    /**
     * allows your app to upload/update builds for a user's applications - requires Discord approval
     */
    const APPLICATIONS_BUILDS_UPLOAD = 'applications.builds.upload';
    /**
     * allows your app to use commands in a guild
     */
    const APPLICATIONS_COMMANDS = 'applications.commands';
    /**
     * allows your app to update its commands via this bearer token - client credentials grant only
     */
    const APPLICATIONS_COMMANDS_UPDATE = 'applications.commands.update';
    /**
     * allows your app to read entitlements for a user's applications
     */
    const APPLICATIONS_ENTITLEMENTS = 'applications.entitlements';
    /**
     * allows your app to read and update store data (SKUs, store listings, achievements, etc.) for a user's applications
     */
    const APPLICATIONS_STORE_UPDATE = 'applications.store.update';
    /**
     * for oauth2 bots, this puts the bot in the user's selected guild by default
     */
    const BOT = 'bot';
    /**
     * allows /users/@me/connections to return linked third-party accounts
     */
    const CONNECTIONS = 'connections';
    /**
     * enables /users/@me to return an email
     */
    const EMAIL = 'email';
    /**
     * allows your app to join users to a group dm
     */
    const GDM_JOIN = 'gdm.join';
    /**
     * allows /users/@me/guilds to return basic information about all of a user's guilds
     */
    const GUILD = 'guilds';
    /**
     * allows /guilds/{guild.id}/members/{user.id} to be used for joining users to a guild
     */
    const GUILDS_JOIN = 'guilds.join';
    /**
     * allows /users/@me/guilds/{guild.id}/member to return a user's member information in a guild
     */
    const GUILDS_MEMBERS_READ = 'guilds.members.read';
    /**
     * allows /users/@me without email
     */
    const IDENTIFY = 'identify';
    /**
     * for local rpc server api access, this allows you to read messages from all client channels (otherwise restricted to channels/guilds your app creates)
     */
    const MESSAGES_READ = 'messages.read';
    /**
     * allows your app to know a user's friends and implicit relationships - requires Discord approval
     */
    const RELATIONSHIPS_READ = 'relationships.read';
    /**
     * for local rpc server access, this allows you to control a user's local Discord client - requires Discord approval
     */
    const RPC = 'rpc';
    /**
     * for local rpc server access, this allows you to update a user's activity - requires Discord approval
     */
    const RPC_ACTIVITIES_WRITE = 'rpc.activities.write';
    /**
     * for local rpc server access, this allows you to receive notifications pushed out to the user - requires Discord approval
     */
    const RPC_NOTIFICATIONS_READ = 'rpc.notifications.read';
    /**
     * for local rpc server access, this allows you to read a user's voice settings and listen for voice events - requires Discord approval
     */
    const RPC_VOICE_READ = 'rpc.voice.read';
    /**
     * for local rpc server access, this allows you to update a user's voice settings - requires Discord approval
     */
    const RPC_VOICE_WRITE = 'rpc.voice.write';
    /**
     * this generates a webhook that is returned in the oauth token response for authorization code grants
     */
    const WEBHOOK_INCOMING = 'webhook.incoming';
}