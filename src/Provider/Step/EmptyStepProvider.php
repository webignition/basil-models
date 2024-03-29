<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Step;

use webignition\BasilModels\Model\Step\StepInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;

class EmptyStepProvider implements StepProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): StepInterface
    {
        throw new UnknownItemException(UnknownItemException::TYPE_STEP, $name);
    }
}
