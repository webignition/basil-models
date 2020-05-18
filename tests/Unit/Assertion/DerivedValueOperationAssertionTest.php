<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Assertion\ComparisonAssertion;
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
        string $derivedComparison,
        string $expectedStringRepresentation
    ) {
        $derivedAssertion = new DerivedValueOperationAssertion($sourceStatement, $identifier, $derivedComparison);

        $this->assertSame($expectedStringRepresentation, $derivedAssertion->getSource());
        $this->assertSame($expectedStringRepresentation, (string) $derivedAssertion);
        $this->assertSame($identifier, $derivedAssertion->getIdentifier());
        $this->assertSame($derivedComparison, $derivedAssertion->getComparison());
        $this->assertSame($sourceStatement, $derivedAssertion->getSourceStatement());
    }

    public function createDataProvider(): array
    {
        return [
            'derived exists from action' => [
                'sourceStatement' => new InteractionAction(
                    'click $".selector"',
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
                'identifier' => '$".selector"',
                'derivedComparison' => 'exists',
                'expectedStringRepresentation' => '$".selector" exists',
            ],
            'derived exists from assertion' => [
                'sourceStatement' => new ComparisonAssertion(
                    '$".selector" is "value',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                'derivedComparison' => 'exists',
                'expectedStringRepresentation' => '$".selector" exists',
            ],
            'derived is-regexp from assertion' => [
                'sourceStatement' => new ComparisonAssertion(
                    '$".selector" matches "value',
                    '$".selector"',
                    'matches',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                'derivedComparison' => 'is-regexp',
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
                    new ComparisonAssertion(
                        '$".selector" is "value',
                        '$".selector',
                        'is',
                        '"value"'
                    ),
                    '$".selector',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'operator' => 'exists',
                    'source_type' => 'assertion',
                    'source' => [
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector',
                        'comparison' => 'is',
                        'value' => '"value"',
                    ],
                    'value' => '$".selector'
                ],
            ],
            'exists from action' => [
                'derivedAssertion' => new DerivedValueOperationAssertion(
                    new InteractionAction(
                        'click $".selector"',
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'operator' => 'exists',
                    'source_type' => 'action',
                    'source' => [
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                    'value' => '$".selector"'
                ],
            ],
            'is-regexp from assertion' => [
                'derivedAssertion' => new DerivedValueOperationAssertion(
                    new ComparisonAssertion(
                        '$".selector" matches "value',
                        '$".selector"',
                        'matches',
                        '"value"'
                    ),
                    'value',
                    'exists'
                ),
                'expectedSerializedData' => [
                    'operator' => 'exists',
                    'source_type' => 'assertion',
                    'source' => [
                        'source' => '$".selector" matches "value',
                        'identifier' => '$".selector"',
                        'comparison' => 'matches',
                        'value' => '"value"',
                    ],
                    'value' => 'value'
                ],
            ],
        ];
    }
}
