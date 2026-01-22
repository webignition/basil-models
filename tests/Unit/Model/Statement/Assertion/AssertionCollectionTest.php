<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Statement\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Statement\Assertion\Assertion;
use webignition\BasilModels\Model\Statement\Assertion\AssertionCollection;
use webignition\BasilModels\Model\Statement\Assertion\AssertionCollectionInterface;

class AssertionCollectionTest extends TestCase
{
    #[DataProvider('countDataProvider')]
    public function testCount(AssertionCollectionInterface $collection, int $expected): void
    {
        self::assertCount($expected, $collection);
    }

    /**
     * @return array<mixed>
     */
    public static function countDataProvider(): array
    {
        return [
            'empty' => [
                'collection' => new AssertionCollection([]),
                'expected' => 0,
            ],
            'one' => [
                'collection' => new AssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                ]),
                'expected' => 1,
            ],
            'three' => [
                'collection' => new AssertionCollection([
                    new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                    new Assertion('$".selector2" exists', 1, '$".selector2"', 'exists'),
                    new Assertion('$".selector3" exists', 2, '$".selector3"', 'exists'),
                ]),
                'expected' => 3,
            ],
        ];
    }
}
