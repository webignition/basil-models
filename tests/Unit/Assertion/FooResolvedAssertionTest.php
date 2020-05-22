<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\FooAssertion;
use webignition\BasilModels\Assertion\FooAssertionInterface;
use webignition\BasilModels\Assertion\FooResolvedAssertion;
use webignition\BasilModels\Assertion\FooResolvedAssertionInterface;

class FooResolvedAssertionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        FooAssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value,
        string $expectedSource
    ) {
        $assertion = new FooResolvedAssertion($sourceAssertion, $identifier, $value);

        $this->assertSame($sourceAssertion, $assertion->getSourceAssertion());
        $this->assertSame($expectedSource, $assertion->getSource());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($sourceAssertion->getOperator(), $assertion->getOperator());
        $this->assertSame($value, $assertion->getValue());
    }

    public function createDataProvider(): array
    {
        return [
            'exists' => [
                'sourceAssertion' => new FooAssertion(
                    '$page_import_name.elements.element_name exists',
                    '$page_import_name.elements.element_name',
                    'exists'
                ),
                'identifier' => '$".resolved"',
                'value' => null,
                'expectedSource' => '$".resolved" exists',
            ],
            'is, scalar value' => [
                'sourceAssertion' => new FooAssertion(
                    '$page_import_name.elements.element_name is "value"',
                    '$page_import_name.elements.element_name',
                    'is',
                    '"value"'
                ),
                'identifier' => '$".resolved"',
                'value' => '"value"',
                'expectedSource' => '$".resolved" is "value"',
            ],
            'is, elemental value' => [
                'sourceAssertion' => new FooAssertion(
                    '$page_import_name.elements.element_name is $page_import_name.elements.value',
                    '$page_import_name.elements.element_name',
                    'is',
                    '$page_import_name.elements.value'
                ),
                'identifier' => '$".resolved"',
                'value' => '$".value"',
                'expectedSource' => '$".resolved" is $".value"',
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param FooResolvedAssertionInterface $assertion
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(FooResolvedAssertionInterface $assertion, array $expectedSerializedData)
    {
        $this->assertEquals($expectedSerializedData, $assertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'from exists assertion' => [
                'derivedAssertion' => new FooResolvedAssertion(
                    new FooAssertion(
                        '$page_import_name.elements.element_name exists',
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'resolved-assertion',
                        'identifier' => '$".selector"',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name exists',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'exists',
                    ],
                ],
            ],
            'from is assertion' => [
                'derivedAssertion' => new FooResolvedAssertion(
                    new FooAssertion(
                        '$page_import_name.elements.element_name is "value"',
                        '$page_import_name.elements.element_name',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'resolved-assertion',
                        'identifier' => '$".selector"',
                        'value' => '"value"',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name is "value"',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'is',
                        'value' => '"value"',
                    ],
                ],
            ],
        ];
    }
}
