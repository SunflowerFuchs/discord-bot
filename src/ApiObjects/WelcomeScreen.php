<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

class WelcomeScreen
{
    /**
     * the server description shown in the welcome screen
     */
    protected string $description;
    /**
     * the channels shown in the welcome screen, up to 5
     * @var WelcomeChannel[]
     */
    protected array $welcome_channels;

    public function __construct(array $data)
    {
        $this->description = $data['description'] ?? '';
        $this->welcome_channels = array_map(fn(array $channelData) => new WelcomeChannel($channelData),
            $data['welcome_channels']);
    }

    /**
     * the server description shown in the welcome screen
     * @return mixed|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * the channels shown in the welcome screen, up to 5
     * @return WelcomeChannel[]
     */
    public function getWelcomeChannels(): array
    {
        return $this->welcome_channels;
    }
}