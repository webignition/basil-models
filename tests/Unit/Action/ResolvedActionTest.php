<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Action\ResolvedActionInterface;

class ResolvedActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        ActionInterface $sourceAction,
        ?string $identifier,
        ?string $value,
        string $expectedSource
    ) {
        $action = new ResolvedAction($sourceAction, $identifier, $value);

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
                'sourceAction' => new Action('back', 'back'),
                'identifier' => null,
                'value' => null,
                'expectedSource' => 'back',
            ],
            'interaction action (click)' => [
                'sourceAction' => new Action('click $page_import_name.elements.element_name', 'click'),
                'identifier' => '$".selector"',
                'value' => null,
                'expectedSource' => 'click $".selector"',
            ],
            'input action (set)' => [
                'sourceAction' => new Action(
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
     * @param ResolvedActionInterface $action
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(ResolvedActionInterface $action, array $expectedSerializedData)
    {
        $this->assertSame($expectedSerializedData, $action->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'browser operation (back)' => [
                'action' => new ResolvedAction(
                    new Action('back', 'back')
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-action',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'back',
                        'type' => 'back',
                    ],
                ],
            ],
            'interaction (click)' => [
                'action' => new ResolvedAction(
                    new Action(
                        'click $page_import_name.elements.element_name',
                        'click',
                        '$page_import_name.elements.element_name',
                        '$page_import_name.elements.element_name'
                    ),
                    '$".selector"'
                ),
                'expectedSerializedData' => [
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
            ],
            'input' => [
                'action' => new ResolvedAction(
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
                'expectedSerializedData' => [
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
            ],
        ];
    }
}
