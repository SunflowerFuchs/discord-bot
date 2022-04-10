<?php

namespace SunflowerFuchs\DiscordBot\Api\Constants;

class MessageFlag
{
    /**
     * this message has been published to subscribed channels (via Channel Following)
     */
    const CROSSPOSTED = 1 << 0;
    /**
     * this message originated from a message in another channel (via Channel Following)
     */
    const IS_CROSSPOST = 1 << 1;
    /**
     * do not include any embeds when serializing this message
     */
    const SUPPRESS_EMBEDS = 1 << 2;
    /**
     * the source message for this crosspost has been deleted (via Channel Following)
     */
    const SOURCE_MESSAGE_DELETED = 1 << 3;
    /**
     * this message came from the urgent message system
     */
    const URGENT = 1 << 4;
    /**
     * this message has an associated thread, with the same id as the message
     */
    const HAS_THREAD = 1 << 5;
    /**
     * this message is only visible to the user who invoked the Interaction
     */
    const EPHEMERAL = 1 << 6;
    /**
     * this message is an Interaction Response and the bot is "thinking"
     */
    const LOADING = 1 << 7;
    /**
     * this message failed to mention some roles and add their members to the thread
     */
    const FAILED_TO_MENTION_SOME_ROLES_IN_THREAD = 1 << 8;
}