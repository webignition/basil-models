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
    /**
     * @param array<mixed> $actionData
     *
     * @return ActionInterface
     *
     * @throws MalformedDataException
     * @throws UnknownActionTypeException
     */
    public function createFromArray(array $actionData): ActionInterface
    {
        $type = $actionData[Action::KEY_TYPE] ?? '';

        if (Action::createsFromType($type)) {
            $action = Action::fromArray($actionData);

            if ($action instanceof ActionInterface) {
                return $action;
            }

            throw new MalformedDataException($actionData);
        }

        if (InteractionAction::createsFromType($type)) {
            $action = InteractionAction::fromArray($actionData);

            if ($action instanceof ActionInterface) {
                return $action;
            }

            throw new MalformedDataException($actionData);
        }

        if (InputAction::createsFromType($type)) {
            $action = InputAction::fromArray($actionData);

            if ($action instanceof ActionInterface) {
                return $action;
            }

            throw new MalformedDataException($actionData);
        }

        if (WaitAction::createsFromType($type)) {
            $action = WaitAction::fromArray($actionData);

            if ($action instanceof ActionInterface) {
                return $action;
            }

            throw new MalformedDataException($actionData);
        }

        throw new UnknownActionTypeException($actionData, $type);
    }
}
