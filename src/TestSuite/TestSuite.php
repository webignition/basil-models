<?php

declare(strict_types=1);

namespace webignition\BasilModels\TestSuite;

use webignition\BasilModels\Test\TestInterface;

class TestSuite implements TestSuiteInterface
{
    private string $name = '';

    /**
     * @var TestInterface[]
     */
    private array $tests = [];

    /**
     * @param string $name
     * @param TestInterface[] $tests
     */
    public function __construct(string $name, array $tests)
    {
        $this->name = $name;

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
