<?php

declare(strict_types=1);

namespace SunflowerFuchs\DiscordBot\Api\Objects;

use SunflowerFuchs\DiscordBot\Helpers\ComponentFactory;

class ActionRowComponent extends Component
{
    const TYPE = 1;

    /**
     * a list of child components
     * @var Component[]
     */
    protected array $components = [];

    public static function fromData(array $data): self
    {
        $that = parent::fromData($data);

        foreach ($data['components'] ?? [] as $componentData) {
            $that->addComponent(ComponentFactory::factory($componentData));
        }

        return $that;
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        $this->components[] = $component;
        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['components'] = array_map(fn(Component $component) => $component->toArray(), $this->components);
        return $array;
    }
}