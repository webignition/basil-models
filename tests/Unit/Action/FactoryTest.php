<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\Factory;
use webignition\BasilModels\Action\ResolvedAction;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    public function testCreateFactory(): void
    {
        $this->assertInstanceOf(Factory::class, Factory::createFactory());
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $actionData
     * @param ActionInterface $expectedAction
     */
    public function testCreateFromArray(array $actionData, ActionInterface $expectedAction): void
    {
        $this->assertEquals($expectedAction, $this->factory->createFromArray($actionData));
    }

    /**
     * @return array[]
     */
    public function createFromArrayDataProvider(): array
    {
        return [
            'back' => [
                'actionData' => [
                    'source' => 'back',
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('back', 'back', ''),
            ],
            'click' => [
                'actionData' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new Action(
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
                'expectedAction' => new Action(
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
                'expectedAction' => new Action(
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
                'expectedAction' => new Action(
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
                'expectedAction' => new Action(
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
                'expectedAction' => new ResolvedAction(
                    new Action('back', 'back')
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
                'expectedAction' => new ResolvedAction(
                    new Action(
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
                'expectedAction' => new ResolvedAction(
                    new Action(
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
     * @param ActionInterface $expectedAction
     */
    public function testCreateFromJson(array $actionData, ActionInterface $expectedAction): void
    {
        $this->assertEquals($expectedAction, $this->factory->createFromJson((string) json_encode($actionData)));
    }
}
