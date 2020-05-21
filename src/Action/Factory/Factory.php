<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action\Factory;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\InputAction;
use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Action\WaitAction;

class Factory
{
    public static function createFactory(): self
    {
        return new Factory();
    }

    /**
     * @param array<mixed> $actionData
     *
     * @return ActionInterface
     */
    public function createFromArray(array $actionData): ActionInterface
    {
        $type = $actionData[Action::KEY_TYPE] ?? '';

        if (in_array($type, ['click', 'submit', 'wait-for'])) {
            return InteractionAction::fromArray($actionData);
        }

        if ('set' === $type) {
            return InputAction::fromArray($actionData);
        }

        if ('wait' === $type) {
            return WaitAction::fromArray($actionData);
        }

        return Action::fromArray($actionData);
    }

    /**
     * @param string $json
     *
     * @return ActionInterface
     */
    public function createFromJson(string $json): ActionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }
}
