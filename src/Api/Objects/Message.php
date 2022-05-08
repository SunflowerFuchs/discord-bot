<?php

declare(strict_types=1);


namespace SunflowerFuchs\DiscordBot\Api\Objects;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Utils;
use SunflowerFuchs\DiscordBot\Api\Constants\MessageFlag;
use SunflowerFuchs\DiscordBot\Api\Constants\MessageType;
use SunflowerFuchs\DiscordBot\Helpers\ComponentFactory;

class Message
{
    /**
     * id of the message
     */
    protected Snowflake $id;
    /**
     * id of the channel the message was sent in
     */
    protected Snowflake $channel_id;
    /**
     * id of the guild the message was sent in
     */
    protected ?Snowflake $guild_id;
    /**
     * the author of this message (not guaranteed to be a valid user, see below)
     */
    protected User $author;
    /**
     * member properties for this message's author
     */
    protected ?GuildMember $member;
    /**
     * contents of the message
     */
    protected string $content;
    /**
     * when this message was sent
     */
    protected int $timestamp;
    /**
     * when this message was edited (or null if never)
     */
    protected int $edited_timestamp;
    /**
     * whether this was a TTS message
     */
    protected bool $tts;
    /**
     * whether this message mentions everyone
     */
    protected bool $mention_everyone;
    /**
     * users specifically mentioned in the message
     * with an additional partial member field
     * @var UserMention[] $mentions
     */
    protected array $mentions;
    /**
     * roles specifically mentioned in this message'
     * @var Snowflake[] $mention_roles
     */
    protected array $mention_roles;
    /**
     * channels specifically mentioned in this message
     * @var ChannelMention[] $mention_channels
     */
    protected array $mention_channels;
    /**
     * any attached files
     * @var Attachment[]
     */
    protected array $attachments;
    /**
     * any embedded content
     * @var Embed[] $embeds
     */
    protected array $embeds;
    /**
     * reactions to the message
     * @var Reaction[] $reactions
     */
    protected array $reactions;
    /**
     * used for validating a message was sent
     */
    protected ?string $nonce;
    /**
     * whether this message is pinned
     */
    protected bool $pinned;
    /**
     * if the message is generated by a webhook, this is the webhook's id
     */
    protected ?Snowflake $webhook_id;
    /**
     * type of message
     * @see MessageType
     */
    protected int $type;
    /**
     * activity, sent with Rich Presence-related chat embeds
     */
    protected ?MessageActivity $activity;
    /**
     * sent with Rich Presence-related chat embeds
     */
    protected ?MessageApplication $application;
    /**
     * reference data sent with crossposted messages and replies
     * @see https://discord.com/developers/docs/resources/channel#message-types
     */
    protected ?MessageReference $message_reference;
    /**
     * message flags combined as a bitfield
     * @see MessageFlag
     */
    protected int $flags;
    /**
     * the message associated with the message_reference
     */
    protected ?Message $referenced_message;
    /**
     * sent if the message is a response to an Interaction
     */
    protected ?MessageInteraction $interaction;
    /**
     * the thread that was started from this message, includes thread member object
     */
    protected ?Channel $thread;
    /**
     * sent if the message contains components like buttons, action rows, or other interactive components
     * @var Component[]
     */
    protected array $components;
    /**
     * the stickers sent with the message (bots currently can only receive messages with stickers, not send)
     * @var StickerItem[] $sticker_items
     */
    protected ?array $sticker_items;

    public function __construct(array $data)
    {
        $this->id = new Snowflake($data['id']);
        $this->channel_id = new Snowflake($data['channel_id']);
        $this->guild_id = !empty($data['guild_id']) ? new Snowflake($data['guild_id']) : null;
        $this->author = new User($data['author']);
        $this->member = !empty($data['member']) ? new GuildMember($data['member']) : null;
        $this->content = $data['content'];
        $this->timestamp = strtotime($data['timestamp']);
        $this->edited_timestamp = !empty($data['edited_timestamp']) ? strtotime($data['edited_timestamp']) : 0;
        $this->tts = $data['tts'] ?? false;
        $this->mention_everyone = $data['mention_everyone'] ?? false;
        $this->nonce = !empty($data['nonce']) ? (string)$data['nonce'] : null;
        $this->pinned = $data['pinned'] ?? false;
        $this->webhook_id = !empty($data['webhook_id']) ? new Snowflake($data['webhook_id']) : null;
        $this->type = $data['type'];
        $this->activity = !empty($data['activity']) ? new MessageActivity($data['activity']) : null;
        $this->application = !empty($data['application']) ? new Application($data['application']) : null;
        $this->message_reference = !empty($data['message_reference']) ? new MessageReference($data['message_reference']) : null;
        $this->flags = $data['flags'] ?? 0;
        $this->referenced_message = !empty($data['referenced_message']) ? new static($data['referenced_message']) : null;
        $this->interaction = !empty($data['interaction']) ? new MessageInteraction($data['interaction']) : null;
        $this->thread = !empty($data['thread']) ? new Channel($data['thread']) : null;

        $this->mentions = array_map(fn(array $mentionData) => new UserMention($mentionData),
            $data['mentions']);
        $this->mention_roles = array_map(fn($snowflake) => new Snowflake($snowflake),
            $data['mention_roles']);
        $this->mention_channels = array_map(fn($mentionData) => new ChannelMention($mentionData),
            $data['mention_channels'] ?? []);
        $this->attachments = array_map(fn($attachmentData) => new Attachment($attachmentData),
            $data['attachments']);
        $this->reactions = array_map(fn($reactionData) => new Reaction($reactionData),
            $data['reactions'] ?? []);
        $this->sticker_items = array_map(fn($itemData) => new StickerItem($itemData),
            $data['sticker_items'] ?? []);
        $this->embeds = array_map(fn($embedData) => new Embed($embedData),
            $data['embeds'] ?? []);
        $this->components = array_map(fn($componentData) => ComponentFactory::factory($componentData),
            $data['components'] ?? []);
    }

    /**
     * @param Client $apiClient
     * @param Snowflake $channelId
     * @param string $content
     * @param bool $tts
     * @param Embed[] $embeds
     * @param ?AllowedMentions $allowedMentions
     * @param ?Snowflake $replyToMessage
     * @param Component[] $components
     * @param Snowflake[] $stickerIds
     * @param string[] $files Array of file paths
     * @param Attachment[] $attachments
     * @param bool $suppressEmbeds
     * @return bool
     * @throws GuzzleException
     */
    public static function create(
        Client $apiClient,
        Snowflake $channelId,
        string $content,
        AllowedMentions $allowedMentions = null,
        bool $tts = false,
        array $embeds = [],
        Snowflake $replyToMessage = null,
        array $components = [],
        array $stickerIds = [],
        array $files = [],
        array $attachments = [],
        bool $suppressEmbeds = false
    ): bool {
        $jsonData = [];
        // Main content
        if (!empty($content)) {
            $jsonData['content'] = $content;
        }
        // TODO: $embed->toArray();
//        if (!empty($embeds)) {
//             $jsonData['embeds'] = array_map(fn(Embed $embed) => $embed->toArray(), $embeds);
//        }
        if (!empty($stickerIds)) {
            $jsonData['sticker_ids'] = array_map(fn(Snowflake $stickerId) => (string)$stickerId, $stickerIds);
        }
        $fileData = [];
        if (!empty($files)) {
            foreach (array_values($files) as $key => $filePath) {
                $fileData[] = [
                    'name' => "files[${key}]",
                    'contents' => Utils::tryFopen($filePath, 'r'),
                    'filename' => basename($filePath)
                ];
            }
        }

        // Additional data
        $jsonData['allowedMentions'] = ($allowedMentions ?? new AllowedMentions())->toArray();
        if (!empty($components)) {
            $jsonData['components'] = array_map(fn(Component $component) => $component->toArray(), $components);
        }
        // TODO: $attachment->toArray();
//        if (!empty($attachments)) {
//             $jsonData['attachments'] = array_map(fn(Attachment $attachment) => $attachment->toArray(), $attachments);
//        }
        if ($tts) {
            $jsonData['tts'] = true;
        }
        if ($suppressEmbeds) {
            $jsonData['flags'] = MessageFlag::SUPPRESS_EMBEDS;
        }
        if (!empty($replyToMessage)) {
            $jsonData['message_reference'] = new MessageReference(['message_id' => $replyToMessage]);
        }

        $options = [
            'multipart' => [
                [
                    'name' => 'payload_json',
                    'contents' => json_encode(
                        $jsonData
                    )
                ],
                ...$fileData
            ],
        ];

        $res = $apiClient->post("channels/${channelId}/messages", $options);
        return $res->getStatusCode() === 200;
    }

    /**
     * id of the message
     * @return Snowflake
     */
    public function getId(): Snowflake
    {
        return $this->id;
    }

    /**
     * id of the channel this message was sent in
     * @return Snowflake
     */
    public function getChannelId(): Snowflake
    {
        return $this->channel_id;
    }

    /**
     * id of the guild this message was sent in
     * @return ?Snowflake
     */
    public function getGuildId(): ?Snowflake
    {
        return $this->guild_id;
    }

    /**
     * the author of the message (might not be a full user object, see {@see Message::isUserMessage()}
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @return ?GuildMember
     */
    public function getMember(): ?GuildMember
    {
        return $this->member;
    }

    /**
     * Returns the full message contents
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Whether the message seems to be a command (starts with the bot prefix)
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function isCommand(string $prefix): bool
    {
        $content = trim($this->getContent());
        $prefixLength = strlen($prefix);
        return $this->isUserMessage()
            && substr($content, 0, $prefixLength) === $prefix
            && strlen($content) > $prefixLength;
    }

    /**
     * If this is a command, returns the command name after the prefix
     *
     * @param string $prefix
     *
     * @return ?string
     */
    public function getCommand(string $prefix): string
    {
        if (!$this->isCommand($prefix)) {
            return '';
        }
        return substr(explode(' ', trim($this->getContent()))[0], strlen($prefix));
    }

    /**
     * If this is a command, returns the parameters this command received
     *
     * @param string $prefix
     *
     * @return string[]
     */
    public function getCommandParams(string $prefix): array
    {
        if (!$this->isCommand($prefix)) {
            return [];
        }
        $params = explode(' ', trim($this->getContent()));
        array_shift($params);
        return $params;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return ?int
     */
    public function getEditedTimestamp(): ?int
    {
        return $this->edited_timestamp;
    }

    /**
     * @return bool
     */
    public function isTts(): bool
    {
        return $this->tts;
    }

    /**
     * @return bool
     */
    public function isMentionEveryone(): bool
    {
        return $this->mention_everyone;
    }

    /**
     * @return User[]
     */
    public function getMentions(): array
    {
        return $this->mentions;
    }

    /**
     * @return Snowflake[]
     */
    public function getMentionRoles(): array
    {
        return $this->mention_roles;
    }

    /**
     * @return ChannelMention[]
     */
    public function getMentionChannels(): ?array
    {
        return $this->mention_channels;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return ?string
     */
    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    /**
     * @return bool
     */
    public function isPinned(): bool
    {
        return $this->pinned;
    }

    /**
     * @return ?Snowflake
     */
    public function getWebhookId(): ?Snowflake
    {
        return $this->webhook_id;
    }

    /**
     * Returns whether the message was sent by a regular user
     *
     * @return bool
     */
    public function isUserMessage(): bool
    {
        return $this->getWebhookId() === null;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Returns whether the message is either a regular message or a reply
     *
     * @return bool
     */
    public function isTextMessage(): bool
    {
        return $this->getType() === MessageType::DEFAULT
            || $this->getType() === MessageType::REPLY
            || $this->getType() === MessageType::THREAD_STARTER_MESSAGE;
    }

    /**
     * @return ?MessageActivity
     */
    public function getActivity(): ?MessageActivity
    {
        return $this->activity;
    }

    /**
     * @return ?MessageApplication
     */
    public function getApplication(): ?MessageApplication
    {
        return $this->application;
    }

    /**
     * @return ?MessageReference
     */
    public function getMessageReference(): ?MessageReference
    {
        return $this->message_reference;
    }

    /**
     * the thread that was started from this message, includes thread member object
     */
    public function getThread(): ?Channel
    {
        return $this->thread;
    }

    /**
     * @return ?int
     */
    public function getFlags(): ?int
    {
        return $this->flags;
    }

    /**
     * If this is a reply, this is the replied to message
     * @return ?Message
     */
    public function getReferencedMessage(): ?Message
    {
        return $this->referenced_message;
    }

    /**
     * @TODO: implement Embeds
     * @return Embed[]
     */
    public function getEmbeds(): array
    {
        return $this->embeds;
    }

    /**
     * @return Reaction[]
     */
    public function getReactions(): ?array
    {
        return $this->reactions;
    }

    /**
     * @return Sticker[]
     */
    public function getStickerItems(): ?array
    {
        return $this->sticker_items;
    }
}