<?php

declare(strict_types=1);

namespace webignition\BasilModels\TestSuite;

use webignition\BasilModels\Test\TestInterface;

interface TestSuiteInterface
{
    public function getName(): string;

    /**
     * @return TestInterface[]
     */
    public function getTests(): array;
}
