<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion\Factory;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\Factory\Factory;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    private Factory $factory;

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
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new InteractionAction(
                        'click $".selector"',
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
            'derived exists from assertion' => [
                'assertionData' => [
                    'operator' => 'exists',
                    'source_type' => 'assertion',
                    'source' => [
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector',
                        'comparison' => 'is',
                        'value' => '"value"',
                    ],
                    'value' => '$".selector"'
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new ComparisonAssertion(
                        '$".selector" is "value',
                        '$".selector',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
        ];
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $assertionData
     * @param AssertionInterface $expectedAssertion
     */
    public function testCreateFromJson(array $assertionData, AssertionInterface $expectedAssertion)
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromJson((string) json_encode($assertionData)));
    }
}
