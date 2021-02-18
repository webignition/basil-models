<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Step;

use webignition\BasilModels\Step\Step;
use webignition\BasilModels\Step\StepCollection;
use webignition\BasilModels\Step\StepCollectionInterface;
use webignition\ObjectReflector\ObjectReflector;

class StepCollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getStepNamesDataProvider
     *
     * @param StepCollectionInterface $collection
     * @param string[] $expectedNames
     */
    public function testGetStepNames(StepCollectionInterface $collection, array $expectedNames): void
    {
        $this->assertSame($expectedNames, $collection->getStepNames());
    }

    /**
     * @return array[]
     */
    public function getStepNamesDataProvider(): array
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
                    'alpha' => new Step([], []),
                    'charlie' => new Step([], []),
                    'zulu' => new Step([], []),
                ]),
                'expectedNames' => [
                    'alpha',
                    'charlie',
                    'zulu',
                ],
            ],
            'provided in reverse alphabetical order' => [
                'collection' => new StepCollection([
                    'zulu' => new Step([], []),
                    'charlie' => new Step([], []),
                    'alpha' => new Step([], []),
                ]),
                'expectedNames' => [
                    'alpha',
                    'charlie',
                    'zulu',
                ],
            ],
        ];
    }

    public function testIterator(): void
    {
        $collection = new StepCollection([
            'alpha' => new Step([], []),
            'charlie' => new Step([], []),
            'zulu' => new Step([], []),
        ]);

        $iteratedSteps = [];

        foreach ($collection as $stepName => $step) {
            $iteratedSteps[$stepName] = $step;
        }

        $this->assertSame(ObjectReflector::getProperty($collection, 'steps'), $iteratedSteps);
    }
}
