<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\ComparisonAssertionInterface;

class ComparisonAssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$".selector" is "value"';
        $identifier = '$".selector"';
        $comparison = 'is';
        $value = '"value"';

        $assertion = new ComparisonAssertion($source, $identifier, $comparison, $value);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($source, (string) $assertion);
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($comparison, $assertion->getComparison());
        $this->assertSame($value, $assertion->getValue());
    }

    /**
     * @dataProvider equalsDataProvider
     */
    public function testEquals(
        ComparisonAssertionInterface $source,
        AssertionInterface $comparator,
        bool $expectedEquals
    ) {
        $this->assertSame($expectedEquals, $source->equals($comparator));
    }

    public function equalsDataProvider(): array
    {
        return [
            'identifiers do not match' => [
                'source' => new ComparisonAssertion('$".source" is "value"', '$".source"', 'is', '"value"'),
                'comparator' => new ComparisonAssertion('$".comparator" is "value"', '$".comparator"', 'is', '"value"'),
                'expectedEquals' => false,
            ],
            'comparisons do not match' => [
                'source' => new ComparisonAssertion('$".source" is "value"', '$".source"', 'is', '"value"'),
                'comparator' => new ComparisonAssertion(
                    '$".comparator" is-not "value"',
                    '$".comparator"',
                    'is-not',
                    '"value"'
                ),
                'expectedEquals' => false,
            ],
            'values do not match' => [
                'source' => new ComparisonAssertion('$".source" is "value"', '$".source"', 'is', '"x"'),
                'comparator' => new ComparisonAssertion('$".source" is "value"', '$".source"', 'is', '"y"'),
                'expectedEquals' => false,
            ],
            'equals' => [
                'source' => new ComparisonAssertion('$".source" is "value"', '$".source"', 'is', '"value"'),
                'comparator' => new ComparisonAssertion('$".source" is "value"', '$".source"', 'is', '"value"'),
                'expectedEquals' => true,
            ],
        ];
    }

    /**
     * @dataProvider normaliseDataProvider
     */
    public function testNormalise(
        ComparisonAssertionInterface $assertion,
        ComparisonAssertionInterface $expectedNormalisedAssertion
    ) {
        $this->assertEquals($expectedNormalisedAssertion, $assertion->normalise());
    }

    public function normaliseDataProvider(): array
    {
        return [
            'is, is in normal form' => [
                'assertion' => new ComparisonAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new ComparisonAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'is, not in normal form' => [
                'assertion' => new ComparisonAssertion(
                    '$import_name.elements.selector is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'expectedNormalisedAssertion' => new ComparisonAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
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
        $this->assertEquals($expectedAssertion, ComparisonAssertion::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedAssertion' => new ComparisonAssertion('', '', '', ''),
            ],
            'identifier missing' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'comparison' => 'is',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion('$".selector" is "value"', '', 'is', '"value"'),
            ],
            'comparison missing' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    '',
                    '"value"'
                ),
            ],
            'value missing' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'is',
                ],
                'expectedAssertion' => new ComparisonAssertion('$".selector" is "value"', '$".selector"', 'is', ''),
            ],
            'source, identifier, comparison, value present' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'is',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
        ];
    }
}
