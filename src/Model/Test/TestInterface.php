<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

use webignition\BasilModels\Model\Step\StepCollectionInterface;

interface TestInterface
{
    public function getPath(): ?string;

    public function getConfiguration(): ConfigurationInterface;

    public function getSteps(): StepCollectionInterface;

    public function withPath(string $path): TestInterface;
}
