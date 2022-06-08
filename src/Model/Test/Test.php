<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

use webignition\BasilModels\Model\Step\StepCollectionInterface;

class Test implements TestInterface
{
    public function __construct(
        private readonly ConfigurationInterface $configuration,
        private readonly StepCollectionInterface $steps,
    ) {
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function getSteps(): StepCollectionInterface
    {
        return $this->steps;
    }
}
