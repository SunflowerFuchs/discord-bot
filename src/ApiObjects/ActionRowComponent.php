<?php

namespace SunflowerFuchs\DiscordBot\ApiObjects;

use SunflowerFuchs\DiscordBot\Helpers\ComponentFactory;

class ActionRowComponent extends Component
{
    const TYPE = 1;

    /**
     * a list of child components
     * @var Component[]
     */
    protected array $components;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->components = array_map(fn($component) => ComponentFactory::factory($component),
            $data['components'] ?? []);
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }
}