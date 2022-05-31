<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider\Step;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\ProviderInterface;
use webignition\BasilModels\Step\StepInterface;

interface StepProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): StepInterface;
}
