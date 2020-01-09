<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\TestSuite;

use webignition\BasilModels\Test\Configuration;
use webignition\BasilModels\Test\Test;
use webignition\BasilModels\Test\TestInterface;
use webignition\BasilModels\TestSuite\TestSuite;

class TestSuiteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $name
     * @param array<mixed> $tests
     * @param TestInterface[] $expectedTests
     */
    public function testCreate(string $name, array $tests, array $expectedTests)
    {
        $testSuite = new TestSuite($name, $tests);

        $this->assertSame($name, $testSuite->getName());
        $this->assertSame($expectedTests, $testSuite->getTests());
    }

    public function createDataProvider(): array
    {
        $testOne = new Test(
            new Configuration('chrome', 'http://example.com/one'),
            []
        );

        $testTwo = new Test(
            new Configuration('chrome', 'http://example.com/two'),
            []
        );

        return [
            'no tests' => [
                'name' => 'no tests',
                'tests' => [],
                'expectedTests' => [],
            ],
            'non-test tests' => [
                'name' => 'non-test tests',
                'tests' => [
                    1,
                    true,
                    'string',
                ],
                'expectedTests' => [],
            ],
            'has tests' => [
                'name' => 'has tests',
                'tests' => [
                    $testOne,
                    $testTwo,
                ],
                'expectedTests' => [
                    $testOne,
                    $testTwo,
                ],
            ],
        ];
    }
}
