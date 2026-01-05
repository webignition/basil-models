<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Action;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Tests\Unit\Model\AbstractStatementTestCase;

class ResolvedActionTest extends AbstractStatementTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(
        ActionInterface $sourceAction,
        ?string $identifier,
        ?string $value,
        string $expectedSource
    ): void {
        $action = new ResolvedAction($sourceAction, $identifier, $value);

        $this->assertSame($sourceAction, $action->getSourceStatement());
        $this->assertSame($expectedSource, $action->getSource());
        $this->assertSame($action->getType(), $sourceAction->getType());
        $this->assertSame($action->getArguments(), $sourceAction->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'browser operation (back)' => [
                'sourceAction' => new Action('back', 0, 'back'),
                'identifier' => null,
                'value' => null,
                'expectedSource' => 'back',
            ],
            'interaction action (click)' => [
                'sourceAction' => new Action('click $page_import_name.elements.element_name', 0, 'click'),
                'identifier' => '$".selector"',
                'value' => null,
                'expectedSource' => 'click $".selector"',
            ],
            'input action (set)' => [
                'sourceAction' => new Action(
                    'set $page_import_name.elements.element_name to "value"',
                    0,
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
     * @return array<mixed>
     */
    public static function jsonSerializeDataProvider(): array
    {
        return [
            'browser operation (back), index=0' => [
                'statement' => new ResolvedAction(
                    new Action('back', 0, 'back')
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-action',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'back',
                        'index' => 0,
                        'type' => 'back',
                    ],
                ],
            ],
            'browser operation (back), index=8' => [
                'statement' => new ResolvedAction(
                    new Action('back', 8, 'back')
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-action',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'back',
                        'index' => 8,
                        'type' => 'back',
                    ],
                ],
            ],
            'interaction (click)' => [
                'statement' => new ResolvedAction(
                    new Action(
                        'click $page_import_name.elements.element_name',
                        0,
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
                        'index' => 0,
                        'type' => 'click',
                        'arguments' => '$page_import_name.elements.element_name',
                        'identifier' => '$page_import_name.elements.element_name',
                    ],
                ],
            ],
            'input' => [
                'statement' => new ResolvedAction(
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
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-action',
                        'identifier' => '$".selector"',
                        'value' => '"value"'
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'set $page_import_name.elements.element_name to "value"',
                        'index' => 0,
                        'type' => 'set',
                        'arguments' => '$page_import_name.elements.element_name to "value"',
                        'identifier' => '$page_import_name.elements.element_name',
                        'value' => '"value"',
                    ],
                ],
            ],
        ];
    }

    public function testIsBrowserOperationIsInteractionIsInputIsWait(): void
    {
        $backAction = new ResolvedAction(
            new Action('back', 0, 'back')
        );

        $forward = new ResolvedAction(
            new Action('forward', 0, 'forward')
        );

        $reload = new ResolvedAction(
            new Action('reload', 0, 'reload')
        );

        $clickAction = new ResolvedAction(
            new Action('click $"s"', 0, 'click', '$"s"', '$"s"')
        );

        $submitAction = new ResolvedAction(
            new Action('submit $"s"', 0, 'submit', '$"s"', '$"s"')
        );

        $waitForAction = new ResolvedAction(
            new Action('wait-for $"s"', 0, 'wait-for', '$"s"', '$"s"')
        );

        $setAction = new ResolvedAction(
            new Action('set $"s" to "v"', 0, 'set', '$"s" to "v"', '$"s"', '"v"')
        );

        $waitAction = new ResolvedAction(
            new Action('wait 1', 0, 'wait', '1', null, '1')
        );

        $this->assertTrue($backAction->isBrowserOperation());
        $this->assertTrue($forward->isBrowserOperation());
        $this->assertTrue($reload->isBrowserOperation());
        $this->assertFalse($clickAction->isBrowserOperation());
        $this->assertFalse($submitAction->isBrowserOperation());
        $this->assertFalse($waitForAction->isBrowserOperation());
        $this->assertFalse($setAction->isBrowserOperation());
        $this->assertFalse($waitAction->isBrowserOperation());

        $this->assertFalse($backAction->isInteraction());
        $this->assertFalse($forward->isInteraction());
        $this->assertFalse($reload->isInteraction());
        $this->assertTrue($clickAction->isInteraction());
        $this->assertTrue($submitAction->isInteraction());
        $this->assertTrue($waitForAction->isInteraction());
        $this->assertFalse($setAction->isInteraction());
        $this->assertFalse($waitAction->isInteraction());

        $this->assertFalse($backAction->isInput());
        $this->assertFalse($forward->isInput());
        $this->assertFalse($reload->isInput());
        $this->assertFalse($clickAction->isInput());
        $this->assertFalse($submitAction->isInput());
        $this->assertFalse($waitForAction->isInput());
        $this->assertTrue($setAction->isInput());
        $this->assertFalse($waitAction->isInput());

        $this->assertFalse($backAction->isWait());
        $this->assertFalse($forward->isWait());
        $this->assertFalse($reload->isWait());
        $this->assertFalse($clickAction->isWait());
        $this->assertFalse($submitAction->isWait());
        $this->assertFalse($waitForAction->isWait());
        $this->assertFalse($setAction->isWait());
        $this->assertTrue($waitAction->isWait());
    }
}
