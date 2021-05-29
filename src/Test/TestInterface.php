<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

use webignition\BasilModels\Step\StepCollectionInterface;

interface TestInterface
{
    public function getPath(): ?string;

    public function getConfiguration(): ConfigurationInterface;

    public function getSteps(): StepCollectionInterface;

    public function withPath(string $path): TestInterface;
}
