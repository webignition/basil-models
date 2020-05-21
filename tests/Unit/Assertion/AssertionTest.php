<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;

class AssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$".selector" exists';
        $identifier = '$".selector"';
        $comparison = 'exists';

        $assertion = new Assertion($source, $identifier, $comparison);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($source, (string) $assertion);
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($comparison, $assertion->getComparison());
    }

    /**
     * @dataProvider equalsDataProvider
     */
    public function testEquals(AssertionInterface $source, AssertionInterface $comparator, bool $expectedEquals)
    {
        $this->assertSame($expectedEquals, $source->equals($comparator));
    }

    public function equalsDataProvider(): array
    {
        return [
            'identifiers do not match' => [
                'source' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'comparator' => new Assertion('$".comparator" exists', '$".comparator"', 'exists'),
                'expectedEquals' => false,
            ],
            'comparisons do not match' => [
                'source' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'comparator' => new Assertion('$".source" not-exists', '$".source"', 'not-exists'),
                'expectedEquals' => false,
            ],
            'equal' => [
                'source' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'comparator' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'expectedEquals' => true,
            ],
        ];
    }

    /**
     * @dataProvider normaliseDataProvider
     */
    public function testNormalise(AssertionInterface $assertion, AssertionInterface $expectedNormalisedAssertion)
    {
        $this->assertEquals($expectedNormalisedAssertion, $assertion->normalise());
    }

    public function normaliseDataProvider(): array
    {
        return [
            'exists, is in normal form' => [
                'assertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'exists, not in normal form' => [
                'assertion' => new Assertion('$import_name.elements.selector exists', '$".selector"', 'exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'not-exists, is in normal form' => [
                'assertion' => new Assertion('$".selector" not-exists', '$".selector"', 'not-exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" not-exists', '$".selector"', 'not-exists'),
            ],
            'not-exists, not in normal form' => [
                'assertion' => new Assertion('$import_name.elements.selector not-exists', '$".selector"', 'not-exists'),
                'expectedNormalisedAssertion' => new Assertion('$".selector" not-exists', '$".selector"', 'not-exists'),
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param AssertionInterface $assertion
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(AssertionInterface $assertion, array $expectedSerializedData)
    {
        $this->assertEquals($expectedSerializedData, $assertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'is' => [
                'assertion' => new ComparisonAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'is',
                    'value' => '"value"',
                ],
            ],
            'is-not' => [
                'assertion' => new ComparisonAssertion(
                    '$".selector" is-not "value"',
                    '$".selector"',
                    'is-not',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" is-not "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'is-not',
                    'value' => '"value"',
                ],
            ],
            'exists' => [
                'assertion' => new Assertion(
                    '$".selector" exists',
                    '$".selector"',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'exists',
                ],
            ],
            'not-exists' => [
                'assertion' => new Assertion(
                    '$".selector" not-exists',
                    '$".selector"',
                    'not-exists'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" not-exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'not-exists',
                ],
            ],
            'includes' => [
                'assertion' => new ComparisonAssertion(
                    '$".selector" includes "value"',
                    '$".selector"',
                    'includes',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" includes "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'includes',
                    'value' => '"value"',
                ],
            ],
            'excludes' => [
                'assertion' => new ComparisonAssertion(
                    '$".selector" excludes "value"',
                    '$".selector"',
                    'excludes',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" excludes "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'excludes',
                    'value' => '"value"',
                ],
            ],
            'matches' => [
                'assertion' => new ComparisonAssertion(
                    '$".selector" matches "/$pattern/"',
                    '$".selector"',
                    'matches',
                    '"/$pattern/"'
                ),
                'expectedSerializedData' => [
                    'source' => '$".selector" matches "/$pattern/"',
                    'identifier' => '$".selector"',
                    'comparison' => 'matches',
                    'value' => '"/$pattern/"',
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param AssertionInterface|null $expectedAssertion
     */
    public function testFromArray(array $data, ?AssertionInterface $expectedAssertion)
    {
        $this->assertEquals($expectedAssertion, Assertion::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedAssertion' => new Assertion('', '', ''),
            ],
            'identifier missing' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'comparison' => 'exists',
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', '', 'exists'),
            ],
            'comparison missing' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', '$".selector"', ''),
            ],
            'source, identifier, comparison present' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'exists',
                ],
                'expectedAssertion' => new Assertion(
                    '$".selector" exists',
                    '$".selector"',
                    'exists'
                ),
            ],
        ];
    }
}
