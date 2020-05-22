<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\FooAction;
use webignition\BasilModels\Action\FooActionInterface;
use webignition\BasilModels\Action\FooFactory;
use webignition\BasilModels\Action\FooResolvedAction;

class FooFactoryTest extends \PHPUnit\Framework\TestCase
{
    private FooFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new FooFactory();
    }

    public function testCreateFactory()
    {
        $this->assertInstanceOf(FooFactory::class, FooFactory::createFactory());
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $actionData
     * @param FooActionInterface $expectedAction
     */
    public function testCreateFromArray(array $actionData, FooActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, $this->factory->createFromArray($actionData));
    }

    public function createFromArrayDataProvider(): array
    {
        return [
            'back' => [
                'actionData' => [
                    'source' => 'back',
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new FooAction('back', 'back', ''),
            ],
            'click' => [
                'actionData' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new FooAction(
                    'click $".selector"',
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'submit' => [
                'actionData' => [
                    'source' => 'submit $".selector"',
                    'type' => 'submit',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new FooAction(
                    'submit $".selector"',
                    'submit',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'wait-for' => [
                'actionData' => [
                    'source' => 'wait-for $".selector"',
                    'type' => 'wait-for',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new FooAction(
                    'wait-for $".selector"',
                    'wait-for',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'set' => [
                'actionData' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => new FooAction(
                    'set $".selector" to "value"',
                    'set',
                    '$".selector"',
                    '$".selector"',
                    '"value"'
                ),
            ],
            'wait' => [
                'actionData' => [
                    'source' => 'wait 30',
                    'type' => 'wait',
                    'arguments' => '30',
                    'value' => '30',
                ],
                'expectedAction' => new FooAction(
                    'wait 30',
                    'wait',
                    '30',
                    null,
                    '30'
                ),
            ],
            'resolved browser operation (back)' => [
                'actionData' => [
                    'container' => [
                        'type' => 'resolved-action',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'back',
                        'type' => 'back',
                    ],
                ],
                'expectedAction' => new FooResolvedAction(
                    new FooAction('back', 'back')
                ),
            ],
            'resolved interaction (click)' => [
                'actionData' => [
                    'container' => [
                        'type' => 'resolved-action',
                        'identifier' => '$".selector"',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'click $page_import_name.elements.element_name',
                        'type' => 'click',
                        'arguments' => '$page_import_name.elements.element_name',
                        'identifier' => '$page_import_name.elements.element_name',
                    ],
                ],
                'expectedAction' => new FooResolvedAction(
                    new FooAction(
                        'click $page_import_name.elements.element_name',
                        'click',
                        '$page_import_name.elements.element_name',
                        '$page_import_name.elements.element_name'
                    ),
                    '$".selector"'
                ),
            ],
            'resolved input (set)' => [
                'actionData' => [
                    'container' => [
                        'type' => 'resolved-action',
                        'identifier' => '$".selector"',
                        'value' => '"value"'
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'set $page_import_name.elements.element_name to "value"',
                        'type' => 'set',
                        'arguments' => '$page_import_name.elements.element_name to "value"',
                        'identifier' => '$page_import_name.elements.element_name',
                        'value' => '"value"',
                    ],
                ],
                'expectedAction' => new FooResolvedAction(
                    new FooAction(
                        'set $page_import_name.elements.element_name to "value"',
                        'set',
                        '$page_import_name.elements.element_name to "value"',
                        '$page_import_name.elements.element_name',
                        '"value"'
                    ),
                    '$".selector"',
                    '"value"'
                ),
            ],
        ];
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $actionData
     * @param FooActionInterface $expectedAction
     */
    public function testCreateFromJson(array $actionData, FooActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, $this->factory->createFromJson((string) json_encode($actionData)));
    }
}
