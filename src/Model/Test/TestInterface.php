<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

use webignition\BasilModels\Model\Step\StepCollectionInterface;

interface TestInterface
{
    public function getConfiguration(): ConfigurationInterface;

    public function getSteps(): StepCollectionInterface;
}
