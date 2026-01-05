<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\Factory as ActionFactory;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\Assertion\Factory;
use webignition\BasilModels\Model\Assertion\ResolvedAssertion;

class FactoryTest extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(
            ActionFactory::createFactory()
        );
    }

    /**
     * @param array<mixed> $data
     */
    #[DataProvider('createFromArrayDataProvider')]
    public function testCreateFromArray(array $data, AssertionInterface $expectedAssertion): void
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromArray($data));
    }

    /**
     * @return array<mixed>
     */
    public static function createFromArrayDataProvider(): array
    {
        return [
            'exists, index=0' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                    'index' => 0,
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
            ],
            'exists, index=6' => [
                'data' => [
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                    'index' => 6,
                ],
                'expectedAssertion' => new Assertion('$".selector" exists', 6, '$".selector"', 'exists'),
            ],
            'is' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'operator' => 'is',
                    'value' => '"value"',
                    'index' => 0,
                ],
                'expectedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    0,
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
                        'index' => 0,
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
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
                        'index' => 0,
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
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
                        'index' => 0,
                    ],
                ],
                'expectedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        0,
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
                        'value' => '$page_import_name.elements.value',
                        'index' => 0,
                    ],
                ],
                'expectedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.selector is $page_import_name.elements.value',
                        0,
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
                            'value' => '$page_import_name.elements.value',
                            'index' => 0,
                        ],
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new ResolvedAssertion(
                        new Assertion(
                            '$page_import_name.elements.selector is $page_import_name.elements.value',
                            0,
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
                            'index' => 0,
                        ],
                    ],
                ],
                'expectedAssertion' => new DerivedValueOperationAssertion(
                    new ResolvedAction(
                        new Action(
                            'click $page_import_name.elements.selector',
                            0,
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
     * @param array<mixed> $data
     */
    #[DataProvider('createFromArrayDataProvider')]
    public function testCreateFromJson(array $data, AssertionInterface $expectedAssertion): void
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromJson((string) json_encode($data)));
    }
}
