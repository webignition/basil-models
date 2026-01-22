<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Statement\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Statement\Assertion\Assertion;
use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Statement\Assertion\UniqueAssertionCollection;

class UniqueAssertionCollectionTest extends TestCase
{
    public function testIterate(): void
    {
        $assertions = [
            new Assertion('$".zero" exists', 0, '$".zero"', 'exists'),
            new Assertion('$".one" exists', 0, '$".one"', 'exists'),
            new Assertion('$".two" exists', 0, '$".two"', 'exists')
        ];

        $collection = new UniqueAssertionCollection($assertions);
        foreach ($collection as $index => $assertion) {
            $this->assertEquals($assertions[$index], $assertion);
        }
    }

    /**
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('isUniqueDataProvider')]
    public function testIsUnique(UniqueAssertionCollection $collection, array $expectedAssertions): void
    {
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
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".zero" exists', 0, '$".zero"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".zero" exists', 0, '$".zero"', 'exists'),
                ],
            ],
            'single item added twice, index difference is correctly ignored' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".zero" exists', 0, '$".zero"', 'exists'),
                    new Assertion('$".zero" exists', 2, '$".zero"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".zero" exists', 0, '$".zero"', 'exists'),
                ],
            ],
            'de-normalised and normalised equivalents' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$import_name.elements.selector exists', 0, '$".selector"', 'exists'),
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('normaliseDataProvider')]
    public function testIsNormalized(UniqueAssertionCollection $collection, array $expectedAssertions): void
    {
        foreach ($collection as $index => $assertion) {
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
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ],
            ],
            'not in normal form' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$import_name.elements.selector exists', 0, '$".selector"', 'exists'),
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('prependDataProvider')]
    public function testPrepend(
        UniqueAssertionCollection $collection,
        UniqueAssertionCollection $additions,
        array $expectedAssertions
    ): void {
        $mergedCollection = $collection->prepend($additions);

        foreach ($mergedCollection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function prependDataProvider(): array
    {
        return [
            'no common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector4" exists', 0, '$".selector4"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector4" exists', 0, '$".selector4"', 'exists'),
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ],
            ],
            'common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 4, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ],
            ],
            'is normalised' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$import_name.elements.selector1 exists', 0, '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ],
            ],
        ];
    }

    /**
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('appendDataProvider')]
    public function testAppend(
        UniqueAssertionCollection $collection,
        UniqueAssertionCollection $additions,
        array $expectedAssertions
    ): void {
        $mergedCollection = $collection->append($additions);

        foreach ($mergedCollection as $index => $assertion) {
            $this->assertEquals($expectedAssertions[$index], $assertion);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function appendDataProvider(): array
    {
        return [
            'no common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector4" exists', 0, '$".selector4"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector4" exists', 0, '$".selector4"', 'exists'),
                ],
            ],
            'common assertions between collections' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 4, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', 4, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                    new Assertion('$".selector3" exists', 0, '$".selector3"', 'exists'),
                ],
            ],
            'is normalised' => [
                'collection' => new UniqueAssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ]),
                'additions' => new UniqueAssertionCollection([
                    new Assertion('$import_name.elements.selector1 exists', 0, '$".selector1"', 'exists'),
                ]),
                'expectedAssertions' => [
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ],
            ],
        ];
    }
}
