<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\StatementInterface;

class DerivedValueOperationAssertionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        StatementInterface $sourceStatement,
        string $identifier,
        string $operator,
        string $expectedStringRepresentation
    ) {
        $derivedAssertion = new DerivedValueOperationAssertion($sourceStatement, $identifier, $operator);

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
                'sourceStatement' => new Action(
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
                'sourceStatement' => new Assertion(
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
                'sourceStatement' => new Assertion(
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
     * @param DerivedValueOperationAssertion $derivedAssertion
     * @param array<mixed> $expectedSerialisedData
     */
    public function testJsonSerialize(
        DerivedValueOperationAssertion $derivedAssertion,
        array $expectedSerialisedData
    ) {
        $this->assertSame($expectedSerialisedData, $derivedAssertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'exists from assertion' => [
                'derivedAssertion' => new DerivedValueOperationAssertion(
                    new Assertion(
                        '$".selector" is "value',
                        '$".selector"',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector"',
                        'operator' => 'is',
                        'value' => '"value"',
                    ],
                ],
            ],
            'exists from action' => [
                'derivedAssertion' => new DerivedValueOperationAssertion(
                    new Action(
                        'click $".selector"',
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                ],
            ],
            'is-regexp from assertion' => [
                'derivedAssertion' => new DerivedValueOperationAssertion(
                    new Assertion(
                        '$".selector" matches "value"',
                        '$".selector"',
                        'matches',
                        '"value"'
                    ),
                    '"value"',
                    'is-regexp'
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '"value"',
                        'operator' => 'is-regexp',
                    ],
                    'statement' => [
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
