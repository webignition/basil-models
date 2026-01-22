<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Statement\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Statement\Assertion\Assertion;
use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;
use webignition\BasilModels\Tests\Unit\Model\Statement\AbstractStatementTestCase;

class AssertionTest extends AbstractStatementTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(string $source, int $index, string $identifier, string $operator, ?string $value): void
    {
        $assertion = new Assertion($source, $index, $identifier, $operator, $value);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($index, $assertion->getIndex());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($operator, $assertion->getOperator());
        $this->assertSame($value, $assertion->getValue());
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'exists' => [
                'source' => '$".selector" exists',
                'index' => 0,
                'identifier' => '$".selector"',
                'operator' => 'exists',
                'value' => null,
            ],
            'is' => [
                'source' => '$".selector" is "value"',
                'index' => 45,
                'identifier' => '$".selector"',
                'operator' => 'is',
                'value' => '"value"',
            ],
        ];
    }

    #[DataProvider('equalsDataProvider')]
    public function testEquals(
        AssertionInterface $assertion,
        AssertionInterface $comparator,
        bool $expectedEquals
    ): void {
        $this->assertSame($expectedEquals, $assertion->equals($comparator));
    }

    /**
     * @return array<mixed>
     */
    public static function equalsDataProvider(): array
    {
        return [
            'identifier not same' => [
                'assertion' => new Assertion('$".selector1" exists', 0, '$".selector1"', 'exists'),
                'comparator' => new Assertion('$".selector2" exists', 0, '$".selector2"', 'exists'),
                'expectedEquals' => false,
            ],
            'operator not same' => [
                'assertion' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                'comparator' => new Assertion('$".selector" not-exists', 0, '$".selector"', 'not-exists'),
                'expectedEquals' => false,
            ],
            'value not same' => [
                'assertion' => new Assertion('$".selector" is "value1"', 0, '$".selector"', 'is', '"value1"'),
                'comparator' => new Assertion('$".selector" is "value2"', 0, '$".selector"', 'is', '"value2"'),
                'expectedEquals' => false,
            ],
            'identifier, operator same' => [
                'assertion' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                'comparator' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                'expectedEquals' => true,
            ],
            'identifier, operator, value same, index same' => [
                'assertion' => new Assertion('$".selector" is "value"', 0, '$".selector"', 'is', '"value"'),
                'comparator' => new Assertion('$".selector" is "value"', 0, '$".selector"', 'is', '"value"'),
                'expectedEquals' => true,
            ],
            'identifier, operator, value same, index not same' => [
                'assertion' => new Assertion('$".selector" is "value"', 1, '$".selector"', 'is', '"value"'),
                'comparator' => new Assertion('$".selector" is "value"', 2, '$".selector"', 'is', '"value"'),
                'expectedEquals' => true,
            ],
        ];
    }

    #[DataProvider('normaliseDataProvider')]
    public function testNormalise(AssertionInterface $assertion, AssertionInterface $expectedNormalisedAssertion): void
    {
        $this->assertEquals($expectedNormalisedAssertion, $assertion->normalise());
    }

    /**
     * @return array<mixed>
     */
    public static function normaliseDataProvider(): array
    {
        return [
            'exists, in normal form' => [
                'assertion' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
            ],
            'exists, not in normal form' => [
                'assertion' => new Assertion('$import_name.elements.selector exists', 0, '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
            ],
            'is, in normal form' => [
                'assertion' => new Assertion(
                    '$".selector" is "value"',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'is, not in normal form' => [
                'assertion' => new Assertion(
                    '$import_name.elements.selector is "value"',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function jsonSerializeDataProvider(): array
    {
        return [
            'exists' => [
                'statement' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                'expectedSerializedData' => [
                    'statement-type' => 'assertion',
                    'source' => '$".selector" exists',
                    'index' => 0,
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                ],
            ],
            'is' => [
                'statement' => new Assertion('$".selector" is "value"', 3, '$".selector"', 'is', '"value"'),
                'expectedSerializedData' => [
                    'statement-type' => 'assertion',
                    'source' => '$".selector" is "value"',
                    'index' => 3,
                    'identifier' => '$".selector"',
                    'operator' => 'is',
                    'value' => '"value"',
                ],
            ],
        ];
    }

    public function testIsComparisonOperator(): void
    {
        $this->assertTrue(Assertion::isComparisonOperator('is'));
        $this->assertTrue(Assertion::isComparisonOperator('is-not'));
        $this->assertFalse(Assertion::isComparisonOperator('exists'));
        $this->assertFalse(Assertion::isComparisonOperator('not-exists'));
        $this->assertTrue(Assertion::isComparisonOperator('includes'));
        $this->assertTrue(Assertion::isComparisonOperator('excludes'));
        $this->assertTrue(Assertion::isComparisonOperator('matches'));
    }

    public function testIsComparison(): void
    {
        $this->assertTrue((new Assertion('$"a" is "a"', 0, '$"a"', 'is', '"a"'))->isComparison());
        $this->assertTrue((new Assertion('$"a" is-not "a"', 0, '$"a"', 'is-not', '"a"'))->isComparison());
        $this->assertFalse((new Assertion('$"a" exists', 0, '$"a"', 'exists'))->isComparison());
        $this->assertFalse((new Assertion('$"a" not-exists', 0, '$"a"', 'not-exists'))->isComparison());
        $this->assertTrue((new Assertion('$"a" includes "a"', 0, '$"a"', 'includes', '"a"'))->isComparison());
        $this->assertTrue((new Assertion('$"a" excludes "a"', 0, '$"a"', 'excludes', '"a"'))->isComparison());
        $this->assertTrue((new Assertion('$"a" matches "a"', 0, '$"a"', 'matches', '"a"'))->isComparison());
    }
}
