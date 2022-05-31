<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Step;

use webignition\BasilModels\Model\Step\StepInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\ProviderInterface;

interface StepProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): StepInterface;
}
