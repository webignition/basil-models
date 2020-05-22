<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\FooAction;
use webignition\BasilModels\Assertion\FooAssertion;
use webignition\BasilModels\Assertion\FooDerivedValueOperationAssertion;
use webignition\BasilModels\FooStatementInterface;

class FooDerivedValueOperationAssertionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        FooStatementInterface $sourceStatement,
        string $identifier,
        string $operator,
        string $expectedStringRepresentation
    ) {
        $derivedAssertion = new FooDerivedValueOperationAssertion($sourceStatement, $identifier, $operator);

        $this->assertSame($expectedStringRepresentation, $derivedAssertion->getSource());
        $this->assertSame($expectedStringRepresentation, (string) $derivedAssertion);
        $this->assertSame($identifier, $derivedAssertion->getIdentifier());
        $this->assertSame($operator, $derivedAssertion->getOperator());
        $this->assertSame($sourceStatement, $derivedAssertion->getSourceStatement());
    }

    public function createDataProvider(): array
    {
        return [
            'derived exists from action' => [
                'sourceStatement' => new FooAction(
                    'click $".selector"',
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
                'identifier' => '$".selector"',
                '$operator' => 'exists',
                'expectedStringRepresentation' => '$".selector" exists',
            ],
            'derived exists from assertion' => [
                'sourceStatement' => new FooAssertion(
                    '$".selector" is "value',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                '$operator' => 'exists',
                'expectedStringRepresentation' => '$".selector" exists',
            ],
            'derived is-regexp from assertion' => [
                'sourceStatement' => new FooAssertion(
                    '$".selector" matches "value',
                    '$".selector"',
                    'matches',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                '$operator' => 'is-regexp',
                'expectedStringRepresentation' => '$".selector" is-regexp',
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param FooDerivedValueOperationAssertion $derivedAssertion
     * @param array<mixed> $expectedSerialisedData
     */
    public function testJsonSerialize(
        FooDerivedValueOperationAssertion $derivedAssertion,
        array $expectedSerialisedData
    ) {
        $this->assertSame($expectedSerialisedData, $derivedAssertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'exists from assertion' => [
                'derivedAssertion' => new FooDerivedValueOperationAssertion(
                    new FooAssertion(
                        '$".selector" is "value',
                        '$".selector"',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'assertion',
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector"',
                        'operator' => 'is',
                        'value' => '"value"',
                    ],
                ],
            ],
            'exists from action' => [
                'derivedAssertion' => new FooDerivedValueOperationAssertion(
                    new FooAction(
                        'click $".selector"',
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'action',
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                ],
            ],
            'is-regexp from assertion' => [
                'derivedAssertion' => new FooDerivedValueOperationAssertion(
                    new FooAssertion(
                        '$".selector" matches "value"',
                        '$".selector"',
                        'matches',
                        '"value"'
                    ),
                    '"value"',
                    'is-regexp'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'derived-value-operation-assertion',
                        'value' => '"value"',
                        'operator' => 'is-regexp',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'assertion',
                        'source' => '$".selector" matches "value"',
                        'identifier' => '$".selector"',
                        'operator' => 'matches',
                        'value' => '"value"',
                    ],
                ],
            ],
        ];
    }
}
