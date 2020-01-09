<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

use webignition\BasilModels\Step\StepInterface;

interface TestInterface
{
    public function getPath(): string;
    public function getConfiguration(): ConfigurationInterface;
    public function getImports(): ImportsInterface;

    /**
     * @return StepInterface[]
     */
    public function getSteps(): array;

    public function withPath(string $path): TestInterface;
}
