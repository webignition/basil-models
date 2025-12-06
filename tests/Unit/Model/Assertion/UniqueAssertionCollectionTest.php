<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Assertion\UniqueAssertionCollection;

class UniqueAssertionCollectionTest extends TestCase
{
    public function testIterate(): void
    {
        $assertions = [
            new Assertion('$".zero" exists', '$".zero"', 'exists'),
            new Assertion('$".one" exists', '$".one"', 'exists'),
            new Assertion('$".two" exists', '$".two"', 'exists')
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
     * @param AssertionInterface[] $assertionsToAdd
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('isUniqueDataProvider')]
    public function testIsUnique(array $assertionsToAdd, array $expectedAssertions): void
    {
        $collection = new UniqueAssertionCollection();

        foreach ($assertionsToAdd as $assertion) {
            $collection->add($assertion);
        }

        foreach ($collection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function isUniqueDataProvider(): array
    {
        return [
            'single item added' => [
                'assertionsToAdd' => [
                    new Assertion('$".zero" exists', '$".zero"', 'exists'),
                ],
                'expectedAssertions' => [
                    new Assertion('$".zero" exists', '$".zero"', 'exists'),
                ],
            ],
            'single item added twice' => [
                'assertionsToAdd' => [
                    new Assertion('$".zero" exists', '$".zero"', 'exists'),
                    new Assertion('$".zero" exists', '$".zero"', 'exists'),
                ],
                'expectedAssertions' => [
                    new Assertion('$".zero" exists', '$".zero"', 'exists'),
                ],
            ],
            'de-normalised and normalised equivalents' => [
                'assertionsToAdd' => [
                    new Assertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                    new Assertion('$".selector" exists', '$".selector"', 'exists'),
                ],
                'expectedAssertions' => [
                    new Assertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @param AssertionInterface[] $assertionsToAdd
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('normaliseDataProvider')]
    public function testNormalise(array $assertionsToAdd, array $expectedAssertions): void
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

    /**
     * @return array<mixed>
     */
    public static function normaliseDataProvider(): array
    {
        return [
            'is in normal form' => [
                'assertionsToAdd' => [
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                ],
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                ],
            ],
            'not in normal form' => [
                'assertionsToAdd' => [
                    new Assertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                    new Assertion('$".selector" exists', '$".selector"', 'exists'),
                ],
                'expectedAssertions' => [
                    new Assertion('$".selector" exists', '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('mergeDataProvider')]
    public function testMerge(
        UniqueAssertionCollection $collection,
        UniqueAssertionCollection $additions,
        array $expectedAssertions
    ): void {
        $mergedCollection = $collection->merge($additions);

        foreach ($mergedCollection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function mergeDataProvider(): array
    {
        return [
            'no common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$".selector3" exists', '$".selector3"', 'exists'),
                    new Assertion('$".selector4" exists', '$".selector4"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                    new Assertion('$".selector3" exists', '$".selector3"', 'exists'),
                    new Assertion('$".selector4" exists', '$".selector4"', 'exists'),
                ],
            ],
            'common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$".selector3" exists', '$".selector3"', 'exists'),
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                    new Assertion('$".selector3" exists', '$".selector3"', 'exists'),
                ],
            ],
            'is normalised' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$import_name.elements.selector1 exists', '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                ],
            ],
        ];
    }
}
