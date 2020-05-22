<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\FooAssertion;
use webignition\BasilModels\Assertion\FooAssertionInterface;

class FooAssertionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, string $identifier, string $operator, ?string $value)
    {
        $assertion = new FooAssertion($source, $identifier, $operator, $value);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($operator, $assertion->getOperator());
        $this->assertSame($value, $assertion->getValue());
    }

    public function createDataProvider(): array
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

    /**
     * @dataProvider equalsDataProvider
     */
    public function testEquals(
        FooAssertionInterface $assertion,
        FooAssertionInterface $comparator,
        bool $expectedEquals
    ) {
        $this->assertSame($expectedEquals, $assertion->equals($comparator));
    }

    public function equalsDataProvider(): array
    {
        return [
            'identifier not same' => [
                'assertion' => new FooAssertion('$".selector1" exists', '$".selector1"', 'exists'),
                'comparator' => new FooAssertion('$".selector2" exists', '$".selector2"', 'exists'),
                'expectedEquals' => false,
            ],
            'operator not same' => [
                'assertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                'comparator' => new FooAssertion('$".selector" not-exists', '$".selector"', 'not-exists'),
                'expectedEquals' => false,
            ],
            'value not same' => [
                'assertion' => new FooAssertion('$".selector" is "value1"', '$".selector"', 'is', '"value1"'),
                'comparator' => new FooAssertion('$".selector" is "value2"', '$".selector"', 'is', '"value2"'),
                'expectedEquals' => false,
            ],
            'identifier, operator same' => [
                'assertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                'comparator' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedEquals' => true,
            ],
            'identifier, operator, value same' => [
                'assertion' => new FooAssertion('$".selector" is "value"', '$".selector"', 'is', '"value"'),
                'comparator' => new FooAssertion('$".selector" is "value"', '$".selector"', 'is', '"value"'),
                'expectedEquals' => true,
            ],
        ];
    }

    /**
     * @dataProvider normaliseDataProvider
     */
    public function testNormalise(FooAssertionInterface $assertion, FooAssertionInterface $expectedNormalisedAssertion)
    {
        $this->assertEquals($expectedNormalisedAssertion, $assertion->normalise());
    }

    public function normaliseDataProvider(): array
    {
        return [
            'exists, in normal form' => [
                'assertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'exists, not in normal form' => [
                'assertion' => new FooAssertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'is, in normal form' => [
                'assertion' => new FooAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new FooAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'is, not in normal form' => [
                'assertion' => new FooAssertion(
                    '$import_name.elements.selector is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new FooAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param FooAssertionInterface $assertion
     * @param array<string, string> $expectedSerializedData
     */
    public function testJsonSerialize(FooAssertionInterface $assertion, array $expectedSerializedData)
    {
        $this->assertSame($expectedSerializedData, $assertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'exists' => [
                'assertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedSerializedData' => [
                    'statement-type' => 'assertion',
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                ],
            ],
            'is' => [
                'assertion' => new FooAssertion('$".selector" is "value"', '$".selector"', 'is', '"value"'),
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
}
