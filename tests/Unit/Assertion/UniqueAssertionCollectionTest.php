<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\FooAssertion;
use webignition\BasilModels\Assertion\FooAssertionInterface;
use webignition\BasilModels\Assertion\UniqueAssertionCollection;

class UniqueAssertionCollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testIterate()
    {
        $assertions = [
            new FooAssertion('$".zero" exists', '$".zero"', 'exists'),
            new FooAssertion('$".one" exists', '$".one"', 'exists'),
            new FooAssertion('$".two" exists', '$".two"', 'exists')
        ];

        $collection = new UniqueAssertionCollection();

        foreach ($assertions as $assertion) {
            $collection->add($assertion);
        }

        foreach ($collection as $index => $assertion) {
            $this->assertSame($assertions[$index], $assertion);
        }
    }

    /**
     * @dataProvider isUniqueDataProvider
     *
     * @param FooAssertionInterface[] $assertionsToAdd
     * @param FooAssertionInterface[] $expectedAssertions
     */
    public function testIsUnique(array $assertionsToAdd, array $expectedAssertions)
    {
        $collection = new UniqueAssertionCollection();

        foreach ($assertionsToAdd as $assertion) {
            $collection->add($assertion);
        }

        foreach ($collection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    public function isUniqueDataProvider(): array
    {
        return [
            'single item added' => [
                'assertionsToAdd' => [
                    new FooAssertion('$".zero" exists', '$".zero"', 'exists'),
                ],
                'expectedAssertions' => [
                    new FooAssertion('$".zero" exists', '$".zero"', 'exists'),
                ],
            ],
            'single item added twice' => [
                'assertionsToAdd' => [
                    new FooAssertion('$".zero" exists', '$".zero"', 'exists'),
                    new FooAssertion('$".zero" exists', '$".zero"', 'exists'),
                ],
                'expectedAssertions' => [
                    new FooAssertion('$".zero" exists', '$".zero"', 'exists'),
                ],
            ],
            'de-normalised and normalised equivalents' => [
                'assertionsToAdd' => [
                    new FooAssertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                    new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                ],
                'expectedAssertions' => [
                    new FooAssertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider normaliseDataProvider
     *
     * @param FooAssertionInterface[] $assertionsToAdd
     * @param FooAssertionInterface[] $expectedAssertions
     */
    public function testNormalise(array $assertionsToAdd, array $expectedAssertions)
    {
        $collection = new UniqueAssertionCollection();

        foreach ($assertionsToAdd as $assertion) {
            $collection->add($assertion);
        }

        $normalisedCollection = $collection->normalise();

        foreach ($normalisedCollection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    public function normaliseDataProvider(): array
    {
        return [
            'is in normal form' => [
                'assertionsToAdd' => [
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                ],
                'expectedAssertions' => [
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                ],
            ],
            'not in normal form' => [
                'assertionsToAdd' => [
                    new FooAssertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                    new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                ],
                'expectedAssertions' => [
                    new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider mergeDataProvider
     *
     * @param UniqueAssertionCollection $collection
     * @param UniqueAssertionCollection $additions
     * @param FooAssertionInterface[] $expectedAssertions
     */
    public function testMerge(
        UniqueAssertionCollection $collection,
        UniqueAssertionCollection $additions,
        array $expectedAssertions
    ) {
        $mergedCollection = $collection->merge($additions);

        foreach ($mergedCollection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    public function mergeDataProvider(): array
    {
        return [
            'no common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new FooAssertion('$".selector3" exists', '$".selector3"', 'exists'),
                    new FooAssertion('$".selector4" exists', '$".selector4"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                    new FooAssertion('$".selector3" exists', '$".selector3"', 'exists'),
                    new FooAssertion('$".selector4" exists', '$".selector4"', 'exists'),
                ],
            ],
            'common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new FooAssertion('$".selector3" exists', '$".selector3"', 'exists'),
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                    new FooAssertion('$".selector3" exists', '$".selector3"', 'exists'),
                ],
            ],
            'is normalised' => [
                'collection' => new UniqueAssertionCollection([
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new FooAssertion('$import_name.elements.selector1 exists', '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                ],
            ],
        ];
    }
}
