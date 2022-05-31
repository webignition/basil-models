<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\TestSuite;

use webignition\BasilModels\Model\Test\TestInterface;

interface TestSuiteInterface
{
    public function getName(): string;

    /**
     * @return TestInterface[]
     */
    public function getTests(): array;
}
