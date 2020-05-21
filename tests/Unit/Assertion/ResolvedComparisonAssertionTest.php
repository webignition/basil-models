<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\ResolvedComparisonAssertion;
use webignition\BasilModels\Assertion\ResolvedComparisonAssertionInterface;

class ResolvedComparisonAssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$page_import_name.elements.identifier is $page_import_name.elements.value';
        $identifier = '$page_import_name.elements.element_name';
        $comparison = 'exists';
        $value = '$page_import_name.elements.value';

        $sourceAssertion = new ComparisonAssertion($source, $identifier, $comparison, $value);

        $resolvedAssertion = new ResolvedComparisonAssertion(
            $sourceAssertion,
            '$".identifier" is $".value"',
            '$".identifier"',
            '$".value"'
        );

        $this->assertSame($sourceAssertion, $resolvedAssertion->getSourceAssertion());
        $this->assertSame($sourceAssertion->getComparison(), $resolvedAssertion->getComparison());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param ResolvedComparisonAssertionInterface $assertion
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(ResolvedComparisonAssertionInterface $assertion, array $expectedSerializedData)
    {
        $this->assertEquals($expectedSerializedData, $assertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'from exists assertion' => [
                'derivedAssertion' => new ResolvedComparisonAssertion(
                    new ComparisonAssertion(
                        '$page_import_name.elements.selector is $page_import_name.elements.value',
                        '$page_import_name.elements.selector',
                        'is',
                        '$page_import_name.elements.value'
                    ),
                    '$".selector" is $".value"',
                    '$".selector"',
                    '$".value"'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'type' => 'resolved-comparison-assertion',
                        'source_type' => 'assertion',
                        'source' => '$".selector" is $".value"',
                        'identifier' => '$".selector"',
                        'value' => '$".value"',
                    ],
                    'encapsulates' => [
                        'source' => '$page_import_name.elements.selector is $page_import_name.elements.value',
                        'identifier' => '$page_import_name.elements.selector',
                        'comparison' => 'is',
                        'value' => '$page_import_name.elements.value'
                    ],
                ],
            ],
        ];
    }
}
