<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\ResolvedAssertion;
use webignition\BasilModels\Assertion\ResolvedAssertionInterface;

class ResolvedAssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$page_import_name.elements.element_name exists';
        $identifier = '$page_import_name.elements.element_name';
        $comparison = 'exists';

        $sourceAssertion = new Assertion($source, $identifier, $comparison);

        $resolvedAssertion = new ResolvedAssertion($sourceAssertion, '$".resolved" exists', '$".resolved"');

        $this->assertSame($sourceAssertion, $resolvedAssertion->getSourceAssertion());
        $this->assertSame($sourceAssertion->getComparison(), $resolvedAssertion->getComparison());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param ResolvedAssertionInterface $assertion
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(ResolvedAssertionInterface $assertion, array $expectedSerializedData)
    {
        $this->assertEquals($expectedSerializedData, $assertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'from exists assertion' => [
                'derivedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector" exists',
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'type' => 'resolved-assertion',
                        'source_type' => 'assertion',
                        'source' => '$".selector" exists',
                        'identifier' => '$".selector"',
                    ],
                    'encapsulates' => [
                        'source' => '$page_import_name.elements.element_name exists',
                        'identifier' => '$page_import_name.elements.element_name',
                        'comparison' => 'exists',
                    ],
                ],
            ],
        ];
    }
}
