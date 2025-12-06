<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Tests\Unit\Model\AbstractStatementTestCase;

class AssertionTest extends AbstractStatementTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(string $source, string $identifier, string $operator, ?string $value): void
    {
        $assertion = new Assertion($source, $identifier, $operator, $value);

        $this->assertSame($source, $assertion->getSource());
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
                'identifier' => '$".selector"',
                'operator' => 'exists',
                'value' => null,
            ],
            'is' => [
                'source' => '$".selector" is "value"',
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
                'assertion' => new Assertion('$".selector1" exists', '$".selector1"', 'exists'),
                'comparator' => new Assertion('$".selector2" exists', '$".selector2"', 'exists'),
                'expectedEquals' => false,
            ],
            'operator not same' => [
                'assertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
                'comparator' => new Assertion('$".selector" not-exists', '$".selector"', 'not-exists'),
                'expectedEquals' => false,
            ],
            'value not same' => [
                'assertion' => new Assertion('$".selector" is "value1"', '$".selector"', 'is', '"value1"'),
                'comparator' => new Assertion('$".selector" is "value2"', '$".selector"', 'is', '"value2"'),
                'expectedEquals' => false,
            ],
            'identifier, operator same' => [
                'assertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
                'comparator' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedEquals' => true,
            ],
            'identifier, operator, value same' => [
                'assertion' => new Assertion('$".selector" is "value"', '$".selector"', 'is', '"value"'),
                'comparator' => new Assertion('$".selector" is "value"', '$".selector"', 'is', '"value"'),
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
                'assertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'exists, not in normal form' => [
                'assertion' => new Assertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'is, in normal form' => [
                'assertion' => new Assertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'is, not in normal form' => [
                'assertion' => new Assertion(
                    '$import_name.elements.selector is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new Assertion(
                    '$".selector" is "value"',
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
                'statement' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedSerializedData' => [
                    'statement-type' => 'assertion',
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                ],
            ],
            'is' => [
                'statement' => new Assertion('$".selector" is "value"', '$".selector"', 'is', '"value"'),
                'expectedSerializedData' => [
                    'statement-type' => 'assertion',
                    'source' => '$".selector" is "value"',
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
        $this->assertTrue((new Assertion('$"a" is "a"', '$"a"', 'is', '"a"'))->isComparison());
        $this->assertTrue((new Assertion('$"a" is-not "a"', '$"a"', 'is-not', '"a"'))->isComparison());
        $this->assertFalse((new Assertion('$"a" exists', '$"a"', 'exists'))->isComparison());
        $this->assertFalse((new Assertion('$"a" not-exists', '$"a"', 'not-exists'))->isComparison());
        $this->assertTrue((new Assertion('$"a" includes "a"', '$"a"', 'includes', '"a"'))->isComparison());
        $this->assertTrue((new Assertion('$"a" excludes "a"', '$"a"', 'excludes', '"a"'))->isComparison());
        $this->assertTrue((new Assertion('$"a" matches "a"', '$"a"', 'matches', '"a"'))->isComparison());
    }
}
