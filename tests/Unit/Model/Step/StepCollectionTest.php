<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Step;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Assertion\AssertionCollection;
use webignition\BasilModels\Model\Step\Step;
use webignition\BasilModels\Model\Step\StepCollection;
use webignition\BasilModels\Model\Step\StepCollectionInterface;
use webignition\ObjectReflector\ObjectReflector;

class StepCollectionTest extends TestCase
{
    /**
     * @param string[] $expectedNames
     */
    #[DataProvider('getStepNamesDataProvider')]
    public function testGetStepNames(StepCollectionInterface $collection, array $expectedNames): void
    {
        $this->assertSame($expectedNames, $collection->getStepNames());
    }

    /**
     * @return array<mixed>
     */
    public static function getStepNamesDataProvider(): array
    {
        return [
            'empty' => [
                'collection' => new StepCollection([]),
                'expectedNames' => [],
            ],
            'invalid' => [
                'collection' => new StepCollection([
                    1,
                    true,
                    'string',
                    new \stdClass(),
                ]),
                'expectedNames' => [],
            ],
            'provided in alphabetical order' => [
                'collection' => new StepCollection([
                    'alpha' => new Step([], new AssertionCollection([])),
                    'charlie' => new Step([], new AssertionCollection([])),
                    'zulu' => new Step([], new AssertionCollection([])),
                ]),
                'expectedNames' => [
                    'alpha',
                    'charlie',
                    'zulu',
                ],
            ],
            'provided in reverse alphabetical order' => [
                'collection' => new StepCollection([
                    'zulu' => new Step([], new AssertionCollection([])),
                    'charlie' => new Step([], new AssertionCollection([])),
                    'alpha' => new Step([], new AssertionCollection([])),
                ]),
                'expectedNames' => [
                    'zulu',
                    'charlie',
                    'alpha',
                ],
            ],
        ];
    }

    public function testIterator(): void
    {
        $collection = new StepCollection([
            'alpha' => new Step([], new AssertionCollection([])),
            'charlie' => new Step([], new AssertionCollection([])),
            'zulu' => new Step([], new AssertionCollection([])),
        ]);

        $iteratedSteps = [];

        foreach ($collection as $stepName => $step) {
            $iteratedSteps[$stepName] = $step;
        }

        $this->assertSame(ObjectReflector::getProperty($collection, 'steps'), $iteratedSteps);
    }
}
