<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\FooAction;
use webignition\BasilModels\Action\FooActionInterface;
use webignition\BasilModels\Action\FooResolvedAction;
use webignition\BasilModels\Action\FooResolvedActionInterface;

class FooResolvedActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        FooActionInterface $sourceAction,
        ?string $identifier,
        ?string $value,
        string $expectedSource
    ) {
        $action = new FooResolvedAction($sourceAction, $identifier, $value);

        $this->assertSame($sourceAction, $action->getSourceAction());
        $this->assertSame($expectedSource, $action->getSource());
        $this->assertSame($action->getType(), $sourceAction->getType());
        $this->assertSame($action->getArguments(), $sourceAction->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }

    public function createDataProvider(): array
    {
        return [
            'browser operation (back)' => [
                'sourceAction' => new FooAction('back', 'back'),
                'identifier' => null,
                'value' => null,
                'expectedSource' => 'back',
            ],
            'interaction action (click)' => [
                'sourceAction' => new FooAction('click $page_import_name.elements.element_name', 'click'),
                'identifier' => '$".selector"',
                'value' => null,
                'expectedSource' => 'click $".selector"',
            ],
            'input action (set)' => [
                'sourceAction' => new FooAction(
                    'set $page_import_name.elements.element_name to "value"',
                    'set',
                    '$page_import_name.elements.element_name to "value"',
                    '$page_import_name.elements.element_name',
                    '"value"'
                ),
                'identifier' => '$".selector"',
                'value' => '"value"',
                'expectedSource' => 'set $".selector" to "value"',
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param FooResolvedActionInterface $action
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(FooResolvedActionInterface $action, array $expectedSerializedData)
    {
        $this->assertSame($expectedSerializedData, $action->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'browser operation (black)' => [
                'action' => new FooResolvedAction(
                    new FooAction('back', 'back')
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'resolved-action',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'action',
                        'source' => 'back',
                        'type' => 'back',
                    ],
                ],
            ],
            'interaction (click)' => [
                'action' => new FooResolvedAction(
                    new FooAction(
                        'click $page_import_name.elements.element_name',
                        'click',
                        '$page_import_name.elements.element_name',
                        '$page_import_name.elements.element_name'
                    ),
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'resolved-action',
                        'identifier' => '$".selector"',
                    ],
                    'encapsulates' => [
                        'statement-type' => 'action',
                        'source' => 'click $page_import_name.elements.element_name',
                        'type' => 'click',
                        'arguments' => '$page_import_name.elements.element_name',
                        'identifier' => '$page_import_name.elements.element_name',
                    ],
                ],
            ],
            'input' => [
                'action' => new FooResolvedAction(
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
                'expectedSerializedData' => [
                    'encapsulation' => [
                        'container' => 'resolved-action',
                        'identifier' => '$".selector"',
                        'value' => '"value"'
                    ],
                    'encapsulates' => [
                        'statement-type' => 'action',
                        'source' => 'set $page_import_name.elements.element_name to "value"',
                        'type' => 'set',
                        'arguments' => '$page_import_name.elements.element_name to "value"',
                        'identifier' => '$page_import_name.elements.element_name',
                        'value' => '"value"',
                    ],
                ],
            ],
        ];
    }
}
