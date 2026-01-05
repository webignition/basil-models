<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Action;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Action\Factory;
use webignition\BasilModels\Model\Action\ResolvedAction;

class FactoryTest extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    /**
     * @param array<mixed> $actionData
     */
    #[DataProvider('createFromArrayDataProvider')]
    public function testCreateFromArray(array $actionData, ActionInterface $expectedAction): void
    {
        $this->assertEquals($expectedAction, $this->factory->createFromArray($actionData));
    }

    /**
     * @return array<mixed>
     */
    public static function createFromArrayDataProvider(): array
    {
        return [
            'back, index=0' => [
                'actionData' => [
                    'source' => 'back',
                    'index' => 0,
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('back', 0, 'back', ''),
            ],
            'back, index=2' => [
                'actionData' => [
                    'source' => 'back',
                    'index' => 2,
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('back', 2, 'back', ''),
            ],
            'click' => [
                'actionData' => [
                    'source' => 'click $".selector"',
                    'index' => 0,
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new Action(
                    'click $".selector"',
                    0,
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'submit' => [
                'actionData' => [
                    'source' => 'submit $".selector"',
                    'index' => 0,
                    'type' => 'submit',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new Action(
                    'submit $".selector"',
                    0,
                    'submit',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'wait-for' => [
                'actionData' => [
                    'source' => 'wait-for $".selector"',
                    'index' => 0,
                    'type' => 'wait-for',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new Action(
                    'wait-for $".selector"',
                    0,
                    'wait-for',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'set' => [
                'actionData' => [
                    'source' => 'set $".selector" to "value"',
                    'index' => 0,
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => new Action(
                    'set $".selector" to "value"',
                    0,
                    'set',
                    '$".selector"',
                    '$".selector"',
                    '"value"'
                ),
            ],
            'wait' => [
                'actionData' => [
                    'source' => 'wait 30',
                    'index' => 0,
                    'type' => 'wait',
                    'arguments' => '30',
                    'value' => '30',
                ],
                'expectedAction' => new Action(
                    'wait 30',
                    0,
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
                        'index' => 0,
                        'source' => 'back',
                        'type' => 'back',
                    ],
                ],
                'expectedAction' => new ResolvedAction(
                    new Action('back', 0, 'back')
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
                        'index' => 0,
                        'source' => 'click $page_import_name.elements.element_name',
                        'type' => 'click',
                        'arguments' => '$page_import_name.elements.element_name',
                        'identifier' => '$page_import_name.elements.element_name',
                    ],
                ],
                'expectedAction' => new ResolvedAction(
                    new Action(
                        'click $page_import_name.elements.element_name',
                        0,
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
                        'index' => 0,
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
                        0,
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
     * @param array<mixed> $actionData
     */
    #[DataProvider('createFromArrayDataProvider')]
    public function testCreateFromJson(array $actionData, ActionInterface $expectedAction): void
    {
        $this->assertEquals($expectedAction, $this->factory->createFromJson((string) json_encode($actionData)));
    }
}
