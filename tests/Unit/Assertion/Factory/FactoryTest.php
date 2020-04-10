<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion\Factory;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedElementExistsAssertion;
use webignition\BasilModels\Assertion\Factory\Factory;
use webignition\BasilModels\Assertion\Factory\MalformedDataException;
use webignition\BasilModels\Assertion\Factory\UnknownComparisonException;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = Factory::createFactory();
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $assertionData
     * @param AssertionInterface $expectedAssertion
     */
    public function testCreateFromArray(array $assertionData, AssertionInterface $expectedAssertion)
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromArray($assertionData));
    }

    public function createFromArrayDataProvider(): array
    {
        return [
            'exists' => [
                'assertionData' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'exists',
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'not-exists' => [
                'assertionData' => [
                    'source' => '$".selector" not-exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'not-exists',
                ],
                'expectedAssertion' => new Assertion('$".selector" not-exists', '$".selector"', 'not-exists'),
            ],
            'is' => [
                'assertionData' => [
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
            'is-not' => [
                'assertionData' => [
                    'source' => '$".selector" is-not "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'is-not',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion(
                    '$".selector" is-not "value"',
                    '$".selector"',
                    'is-not',
                    '"value"'
                ),
            ],
            'includes' => [
                'assertionData' => [
                    'source' => '$".selector" includes "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'includes',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion(
                    '$".selector" includes "value"',
                    '$".selector"',
                    'includes',
                    '"value"'
                ),
            ],
            'excludes' => [
                'assertionData' => [
                    'source' => '$".selector" excludes "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'excludes',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion(
                    '$".selector" excludes "value"',
                    '$".selector"',
                    'excludes',
                    '"value"'
                ),
            ],
            'matches' => [
                'assertionData' => [
                    'source' => '$".selector" matches "value"',
                    'identifier' => '$".selector"',
                    'comparison' => 'matches',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new ComparisonAssertion(
                    '$".selector" matches "value"',
                    '$".selector"',
                    'matches',
                    '"value"'
                ),
            ],
            'derived exists from action' => [
                'assertionData' => [
                    'source_type' => 'action',
                    'source' => [
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                    'identifier' => '$".selector"'
                ],
                'expectedAssertion' => new DerivedElementExistsAssertion(
                    new InteractionAction(
                        'click $".selector"',
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"'
                ),
            ],
            'derived exists from assertion' => [
                'assertionData' => [
                    'source_type' => 'assertion',
                    'source' => [
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector',
                        'comparison' => 'is',
                        'value' => '"value"',
                    ],
                    'identifier' => '$".selector"'
                ],
                'expectedAssertion' => new DerivedElementExistsAssertion(
                    new ComparisonAssertion(
                        '$".selector" is "value',
                        '$".selector',
                        'is',
                        '"value"'
                    ),
                    '$".selector"'
                ),
            ],
        ];
    }

    /**
     * @dataProvider createFromArrayThrowsMalformedDataExceptionDataProvider
     *
     * @param array<mixed> $assertionData
     */
    public function testCreateFromArrayThrowsMalformedDataException(array $assertionData)
    {
        try {
            $this->factory->createFromArray($assertionData);
            $this->fail('MalformedDataException not thrown');
        } catch (MalformedDataException $malformedDataException) {
            $this->assertSame($assertionData, $malformedDataException->getData());
        }
    }

    public function createFromArrayThrowsMalformedDataExceptionDataProvider(): array
    {
        return [
            'malformed assertion (lacking source, identifier)' => [
                'assertionData' => [
                    'comparison' => 'exists',
                ],
            ],
            'malformed comparison assertion (lacking source, identifier)' => [
                'assertionData' => [
                    'comparison' => 'is',
                ],
            ],
            'malformed comparison assertion (lacking value)' => [
                'assertionData' => [
                    'source' => '$".selector" is "value"',
                    'comparison' => 'is',
                    'identifier' => '$".selector"',
                ],
            ],
            'malformed derived assertion (lacking identifier)' => [
                'assertionData' => [
                    'source_type' => 'action',
                    'source' => [
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                ],
            ],
            'malformed derived assertion (lacking source)' => [
                'assertionData' => [
                    'source_type' => 'action',
                    'identifier' => '$".selector"',
                ],
            ],
        ];
    }

    /**
     * @dataProvider createFromArrayThrowsUnknownComparisonExceptionDataProvider
     *
     * @param array<mixed> $assertionData
     */
    public function testCreateFromArrayThrowsUnknownComparisonException(array $assertionData)
    {
        try {
            $this->factory->createFromArray($assertionData);
            $this->fail('UnknownComparisonException not thrown');
        } catch (UnknownComparisonException $unknownComparisonException) {
            $this->assertSame($assertionData, $unknownComparisonException->getData());
            $this->assertSame($assertionData['comparison'] ?? '', $unknownComparisonException->getComparison());
        }
    }

    public function createFromArrayThrowsUnknownComparisonExceptionDataProvider(): array
    {
        return [
            'empty' => [
                'assertionData' => [],
            ],
            'unknown comparison' => [
                'assertionData' => [
                    'comparison' => 'foo',
                ],
            ],
        ];
    }
}
