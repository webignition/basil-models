<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion\Factory;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\Factory\Factory;
use webignition\BasilModels\Assertion\ResolvedAssertion;
use webignition\BasilModels\Assertion\ResolvedComparisonAssertion;

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
     * @param array<mixed> $data
     * @param AssertionInterface $expectedAssertion
     */
    public function testCreateFromArray(array $data, AssertionInterface $expectedAssertion)
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromArray($data));
    }

    public function createFromArrayDataProvider(): array
    {
        return [
            'exists' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'exists',
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'not-exists' => [
                'data' => [
                    'source' => '$".selector" not-exists',
                    'identifier' => '$".selector"',
                    'comparison' => 'not-exists',
                ],
                'expectedAssertion' => new Assertion('$".selector" not-exists', '$".selector"', 'not-exists'),
            ],
            'is' => [
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
            'is-not' => [
                'data' => [
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
                'data' => [
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
                'data' => [
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
                'data' => [
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
                'data' => [
                    'encapsulation' => [
                        'type' => 'derived-value-operation-assertion',
                        'source_type' => 'action',
                        'operator' => 'exists',
                        'value' => '$".selector"',
                    ],
                    'encapsulates' => [
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
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
                'data' => [
                    'encapsulation' => [
                        'type' => 'derived-value-operation-assertion',
                        'source_type' => 'assertion',
                        'operator' => 'exists',
                        'value' => '$".selector"',
                    ],
                    'encapsulates' => [
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector"',
                        'comparison' => 'is',
                        'value' => '"value"',
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new ComparisonAssertion(
                        '$".selector" is "value',
                        '$".selector"',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
            'resolved exists assertion' => [
                'data' => [
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
                'expectedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector" exists',
                    '$".selector"'
                ),
            ],
            'resolved is assertion' => [
                'data' => [
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
                'expectedAssertion' => new ResolvedComparisonAssertion(
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
