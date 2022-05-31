<?php

declare(strict_types=1);

namespace webignition\BasilModels\TestSuite;

use webignition\BasilModels\Test\TestInterface;

class TestSuite implements TestSuiteInterface
{
    /**
     * @var TestInterface[]
     */
    private array $tests = [];

    /**
     * @param array<mixed> $tests
     */
    public function __construct(
        private readonly string $name,
        array $tests
    ) {
        foreach ($tests as $test) {
            if ($test instanceof TestInterface) {
                $this->tests[] = $test;
            }
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TestInterface[]
     */
    public function getTests(): array
    {
        return $this->tests;
    }
}
