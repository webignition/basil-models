<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\Factory as ActionFactory;
use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\Factory;
use webignition\BasilModels\Assertion\ResolvedAssertion;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(
            ActionFactory::createFactory()
        );
    }

    public function testCreateFactory(): void
    {
        $this->assertInstanceOf(Factory::class, Factory::createFactory());
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $data
     */
    public function testCreateFromArray(array $data, AssertionInterface $expectedAssertion): void
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromArray($data));
    }

    /**
     * @return array[]
     */
    public function createFromArrayDataProvider(): array
    {
        return [
            'exists' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'is' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'operator' => 'is',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'derived exists from action' => [
                'data' => [
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
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new Action(
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
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new Assertion(
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
                    'container' => [
                        'type' => 'resolved-assertion',
                        'source' => '$".selector" exists',
                        'identifier' => '$".selector"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name exists',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'exists',
                    ],
                ],
                'expectedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector"'
                ),
            ],
            'resolved is assertion' => [
                'data' => [
                    'container' => [
                        'type' => 'resolved-assertion',
                        'source' => '$".selector" is $".value"',
                        'identifier' => '$".selector"',
                        'value' => '$".value"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.selector is $page_import_name.elements.value',
                        'identifier' => '$page_import_name.elements.selector',
                        'operator' => 'is',
                        'value' => '$page_import_name.elements.value'
                    ],
                ],
                'expectedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.selector is $page_import_name.elements.value',
                        '$page_import_name.elements.selector',
                        'is',
                        '$page_import_name.elements.value'
                    ),
                    '$".selector"',
                    '$".value"'
                ),
            ],
            'derived exists from resolved assertion' => [
                'data' => [
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'container' => [
                            'type' => 'resolved-assertion',
                            'source' => '$".selector" is $".value"',
                            'identifier' => '$".selector"',
                            'value' => '$".value"',
                        ],
                        'statement' => [
                            'statement-type' => 'assertion',
                            'source' => '$page_import_name.elements.selector is $page_import_name.elements.value',
                            'identifier' => '$page_import_name.elements.selector',
                            'operator' => 'is',
                            'value' => '$page_import_name.elements.value'
                        ],
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new ResolvedAssertion(
                        new Assertion(
                            '$page_import_name.elements.selector is $page_import_name.elements.value',
                            '$page_import_name.elements.selector',
                            'is',
                            '$page_import_name.elements.value'
                        ),
                        '$".selector"',
                        '$".value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
            'derived exists from resolved action' => [
                'data' => [
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'container' => [
                            'type' => 'resolved-action',
                            'identifier' => '$".selector"',
                        ],
                        'statement' => [
                            'statement-type' => 'action',
                            'source' => 'click $page_import_name.elements.selector',
                            'type' => 'click',
                            'arguments' => '$page_import_name.elements.selector',
                            'identifier' => '$page_import_name.elements.selector',
                        ],
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new ResolvedAction(
                        new Action(
                            'click $page_import_name.elements.selector',
                            'click',
                            '$page_import_name.elements.selector',
                            '$page_import_name.elements.selector'
                        ),
                        '$".selector"'
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
     */
    public function testCreateFromJson(array $assertionData, AssertionInterface $expectedAssertion): void
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromJson((string) json_encode($assertionData)));
    }
}
