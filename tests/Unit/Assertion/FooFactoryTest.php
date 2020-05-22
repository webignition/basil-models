<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\FooAction;
use webignition\BasilModels\Action\FooFactory as ActionFactory;
use webignition\BasilModels\Assertion\FooAssertion;
use webignition\BasilModels\Assertion\FooAssertionInterface;
use webignition\BasilModels\Assertion\FooDerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\FooFactory;
use webignition\BasilModels\Assertion\FooResolvedAssertion;

class FooFactoryTest extends \PHPUnit\Framework\TestCase
{
    private FooFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new FooFactory(
            ActionFactory::createFactory()
        );
    }

    public function testCreateFactory()
    {
        $this->assertInstanceOf(FooFactory::class, FooFactory::createFactory());
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param FooAssertionInterface $expectedAssertion
     */
    public function testCreateFromArray(array $data, FooAssertionInterface $expectedAssertion)
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
                    'operator' => 'exists',
                ],
                'expectedAssertion' => new FooAssertion('$".selector" exists', '$".selector"', 'exists'),
            ],
            'is' => [
                'data' => [
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'operator' => 'is',
                    'value' => '"value"',
                ],
                'expectedAssertion' => new FooAssertion(
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
                'expectedAssertion' => new FooDerivedValueOperationAssertion(
                    new FooAction(
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
                'expectedAssertion' => new FooDerivedValueOperationAssertion(
                    new FooAssertion(
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
                'expectedAssertion' => new FooResolvedAssertion(
                    new FooAssertion(
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
                'expectedAssertion' => new FooResolvedAssertion(
                    new FooAssertion(
                        '$page_import_name.elements.selector is $page_import_name.elements.value',
                        '$page_import_name.elements.selector',
                        'is',
                        '$page_import_name.elements.value'
                    ),
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
     * @param FooAssertionInterface $expectedAssertion
     */
    public function testCreateFromJson(array $assertionData, FooAssertionInterface $expectedAssertion)
    {
        $this->assertEquals($expectedAssertion, $this->factory->createFromJson((string) json_encode($assertionData)));
    }
}
