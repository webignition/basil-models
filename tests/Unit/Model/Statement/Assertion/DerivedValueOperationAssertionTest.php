<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Statement\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Statement\Action\Action;
use webignition\BasilModels\Model\Statement\Assertion\Assertion;
use webignition\BasilModels\Model\Statement\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\Statement\StatementInterface;
use webignition\BasilModels\Tests\Unit\Model\Statement\AbstractStatementTestCase;

class DerivedValueOperationAssertionTest extends AbstractStatementTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(
        StatementInterface $sourceStatement,
        string $identifier,
        string $operator,
        string $expectedStringRepresentation
    ): void {
        $derivedAssertion = new DerivedValueOperationAssertion($sourceStatement, $identifier, $operator);

        $this->assertSame($expectedStringRepresentation, $derivedAssertion->getSource());
        $this->assertSame($expectedStringRepresentation, (string) $derivedAssertion);
        $this->assertSame($identifier, $derivedAssertion->getIdentifier());
        $this->assertSame($operator, $derivedAssertion->getOperator());
        $this->assertSame($sourceStatement, $derivedAssertion->getSourceStatement());
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'derived exists from action' => [
                'sourceStatement' => new Action(
                    'click $".selector"',
                    0,
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
                'identifier' => '$".selector"',
                'operator' => 'exists',
                'expectedStringRepresentation' => '$".selector" exists',
            ],
            'derived exists from assertion' => [
                'sourceStatement' => new Assertion(
                    '$".selector" is "value',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                'operator' => 'exists',
                'expectedStringRepresentation' => '$".selector" exists',
            ],
            'derived is-regexp from assertion' => [
                'sourceStatement' => new Assertion(
                    '$".selector" matches "value',
                    0,
                    '$".selector"',
                    'matches',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                'operator' => 'is-regexp',
                'expectedStringRepresentation' => '$".selector" is-regexp',
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function jsonSerializeDataProvider(): array
    {
        return [
            'exists from assertion, index=0' => [
                'statement' => new DerivedValueOperationAssertion(
                    new Assertion(
                        '$".selector" is "value',
                        0,
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
                        'index' => 0,
                        'identifier' => '$".selector"',
                        'operator' => 'is',
                        'value' => '"value"',
                    ],
                ],
            ],
            'exists from assertion, index=4' => [
                'statement' => new DerivedValueOperationAssertion(
                    new Assertion(
                        '$".selector" is "value',
                        4,
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
                        'index' => 4,
                        'identifier' => '$".selector"',
                        'operator' => 'is',
                        'value' => '"value"',
                    ],
                ],
            ],
            'exists from action' => [
                'statement' => new DerivedValueOperationAssertion(
                    new Action(
                        'click $".selector"',
                        0,
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
                        'index' => 0,
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                ],
            ],
            'is-regexp from assertion' => [
                'statement' => new DerivedValueOperationAssertion(
                    new Assertion(
                        '$".selector" matches "value"',
                        0,
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
                        'index' => 0,
                        'identifier' => '$".selector"',
                        'operator' => 'matches',
                        'value' => '"value"',
                    ],
                ],
            ],
        ];
    }

    public function testIsComparison(): void
    {
        $source = new Assertion('$"a" foo', 0, '$"a"', 'foo');

        $this->assertTrue((new DerivedValueOperationAssertion($source, '$"a"', 'is'))->isComparison());
        $this->assertTrue((new DerivedValueOperationAssertion($source, '$"a"', 'is-not'))->isComparison());
        $this->assertFalse((new DerivedValueOperationAssertion($source, '$"a"', 'exists'))->isComparison());
        $this->assertFalse((new DerivedValueOperationAssertion($source, '$"a"', 'not-exists'))->isComparison());
        $this->assertTrue((new DerivedValueOperationAssertion($source, '$"a"', 'includes'))->isComparison());
        $this->assertTrue((new DerivedValueOperationAssertion($source, '$"a"', 'excludes'))->isComparison());
        $this->assertTrue((new DerivedValueOperationAssertion($source, '$"a"', 'matches'))->isComparison());
    }
}
