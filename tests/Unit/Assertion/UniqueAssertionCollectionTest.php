<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\UniqueAssertionCollection;

class UniqueAssertionCollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testIterate()
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
     * @dataProvider isUniqueDataProvider
     *
     * @param AssertionInterface[] $assertionsToAdd
     * @param AssertionInterface[] $expectedAssertions
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
     * @dataProvider normaliseDataProvider
     *
     * @param AssertionInterface[] $assertionsToAdd
     * @param AssertionInterface[] $expectedAssertions
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
}
