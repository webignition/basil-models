<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\EncapsulatingActionInterface;
use webignition\BasilModels\Action\ResolvedAction;

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
     * @return array[]
     */
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
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(EncapsulatingActionInterface $action, array $expectedSerializedData): void
    {
        $serializedStatement = $action->jsonSerialize();

        ksort($serializedStatement);
        ksort($expectedSerializedData);

        self::assertArrayHasKey('statement', $serializedStatement);
        self::assertArrayHasKey('statement', $expectedSerializedData);

        $serializedStatementStatement = $serializedStatement['statement'];
        ksort($serializedStatementStatement);
        $serializedStatement['statement'] = $serializedStatementStatement;

        $expectedSerializedDataStatement = $expectedSerializedData['statement'];
        ksort($expectedSerializedDataStatement);
        $expectedSerializedData['statement'] = $expectedSerializedDataStatement;

        self::assertSame($expectedSerializedData, $serializedStatement);
    }

    /**
     * @return array[]
     */
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

    public function testIsBrowserOperationIsInteractionIsInputIsWait(): void
    {
        $backAction = new ResolvedAction(
            new Action('back', 'back')
        );

        $forward = new ResolvedAction(
            new Action('forward', 'forward')
        );

        $reload = new ResolvedAction(
            new Action('reload', 'reload')
        );

        $clickAction = new ResolvedAction(
            new Action('click $"s"', 'click', '$"s"', '$"s"')
        );

        $submitAction = new ResolvedAction(
            new Action('submit $"s"', 'submit', '$"s"', '$"s"')
        );

        $waitForAction = new ResolvedAction(
            new Action('wait-for $"s"', 'wait-for', '$"s"', '$"s"')
        );

        $setAction = new ResolvedAction(
            new Action('set $"s" to "v"', 'set', '$"s" to "v"', '$"s"', '"v"')
        );

        $waitAction = new ResolvedAction(
            new Action('wait 1', 'wait', '1', null, '1')
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
