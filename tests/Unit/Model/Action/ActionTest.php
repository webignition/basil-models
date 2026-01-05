<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Action;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Tests\Unit\Model\AbstractStatementTestCase;

class ActionTest extends AbstractStatementTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(
        string $source,
        int $index,
        string $type,
        ?string $arguments,
        ?string $identifier,
        ?string $value
    ): void {
        $action = new Action($source, $index, $type, $arguments, $identifier, $value);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($index, $action->getIndex());
        $this->assertSame($type, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'action-only' => [
                'source' => 'back',
                'index' => 0,
                'type' => 'back',
                'arguments' => null,
                'identifier' => null,
                'value' => null,
            ],
            'interaction' => [
                'source' => 'click $".selector"',
                'index' => 0,
                'type' => 'click',
                'arguments' => '$".selector"',
                'identifier' => '$".selector"',
                'value' => null,
            ],
            'input' => [
                'source' => 'set $".selector" to "value"',
                'index' => 0,
                'type' => 'set',
                'arguments' => '$".selector"',
                'identifier' => '$".selector"',
                'value' => '"value"',
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function jsonSerializeDataProvider(): array
    {
        return [
            'action-only, index=0' => [
                'statement' => new Action('back', 0, 'back'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'back',
                    'type' => 'back',
                    'index' => 0,
                ],
            ],
            'action-only, index=3' => [
                'statement' => new Action('back', 3, 'back'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'back',
                    'type' => 'back',
                    'index' => 3,
                ],
            ],
            'interaction' => [
                'statement' => new Action('click $".selector"', 0, 'click', '$".selector"', '$".selector"'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'index' => 0,
                ],
            ],
            'input' => [
                'statement' => new Action(
                    'set $".selector" to "value"',
                    0,
                    'set',
                    '$".selector" to "value"',
                    '$".selector"',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector" to "value"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                    'index' => 0,
                ],
            ],
            'wait' => [
                'statement' => new Action('wait', 0, 'wait', '30', null, '30'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'wait',
                    'type' => 'wait',
                    'arguments' => '30',
                    'value' => '30',
                    'index' => 0,
                ],
            ],
        ];
    }

    public function testIsBrowserOperationType(): void
    {
        $this->assertTrue(Action::isBrowserOperationType('back'));
        $this->assertTrue(Action::isBrowserOperationType('forward'));
        $this->assertTrue(Action::isBrowserOperationType('reload'));
        $this->assertFalse(Action::isBrowserOperationType('click'));
        $this->assertFalse(Action::isBrowserOperationType('submit'));
        $this->assertFalse(Action::isBrowserOperationType('wait-for'));
        $this->assertFalse(Action::isBrowserOperationType('set'));
        $this->assertFalse(Action::isBrowserOperationType('wait'));
    }

    public function testIsInteractionType(): void
    {
        $this->assertFalse(Action::isInteractionType('back'));
        $this->assertFalse(Action::isInteractionType('forward'));
        $this->assertFalse(Action::isInteractionType('reload'));
        $this->assertTrue(Action::isInteractionType('click'));
        $this->assertTrue(Action::isInteractionType('submit'));
        $this->assertTrue(Action::isInteractionType('wait-for'));
        $this->assertFalse(Action::isInteractionType('set'));
        $this->assertFalse(Action::isInteractionType('wait'));
    }

    public function testIsInputType(): void
    {
        $this->assertFalse(Action::isInputType('back'));
        $this->assertFalse(Action::isInputType('forward'));
        $this->assertFalse(Action::isInputType('reload'));
        $this->assertFalse(Action::isInputType('click'));
        $this->assertFalse(Action::isInputType('submit'));
        $this->assertFalse(Action::isInputType('wait-for'));
        $this->assertTrue(Action::isInputType('set'));
        $this->assertFalse(Action::isInputType('wait'));
    }

    public function testIsWaitType(): void
    {
        $this->assertFalse(Action::isWaitType('back'));
        $this->assertFalse(Action::isWaitType('forward'));
        $this->assertFalse(Action::isWaitType('reload'));
        $this->assertFalse(Action::isWaitType('click'));
        $this->assertFalse(Action::isWaitType('submit'));
        $this->assertFalse(Action::isWaitType('wait-for'));
        $this->assertFalse(Action::isWaitType('set'));
        $this->assertTrue(Action::isWaitType('wait'));
    }

    public function testIsBrowserOperation(): void
    {
        $this->assertTrue(new Action('back', 0, 'back')->isBrowserOperation());
        $this->assertTrue(new Action('forward', 0, 'forward')->isBrowserOperation());
        $this->assertTrue(new Action('reload', 0, 'reload')->isBrowserOperation());
        $this->assertFalse(new Action('click $"s"', 0, 'click', '$"s"', '$"s"')->isBrowserOperation());
        $this->assertFalse(new Action('submit $"s"', 0, 'submit', '$"s"', '$"s"')->isBrowserOperation());
        $this->assertFalse(new Action('wait-for $"s"', 0, 'wait-for', '$"s"', '$"s"')->isBrowserOperation());
        $this->assertFalse(new Action('set $"s" to "v"', 0, 'set', '$"s" to "v"', '$"s"', '"v"')->isBrowserOperation());
        $this->assertFalse(new Action('wait 1', 0, 'wait', '1', null, '1')->isBrowserOperation());
    }

    public function testIsInteraction(): void
    {
        $this->assertFalse(new Action('back', 0, 'back')->isInteraction());
        $this->assertFalse(new Action('forward', 0, 'forward')->isInteraction());
        $this->assertFalse(new Action('reload', 0, 'reload')->isInteraction());
        $this->assertTrue(new Action('click $"s"', 0, 'click', '$"s"', '$"s"')->isInteraction());
        $this->assertTrue(new Action('submit $"s"', 0, 'submit', '$"s"', '$"s"')->isInteraction());
        $this->assertTrue(new Action('wait-for $"s"', 0, 'wait-for', '$"s"', '$"s"')->isInteraction());
        $this->assertFalse(new Action('set $"s" to "v"', 0, 'set', '$"s" to "v"', '$"s"', '"v"')->isInteraction());
        $this->assertFalse(new Action('wait 1', 0, 'wait', '1', null, '1')->isInteraction());
    }

    public function testIsInput(): void
    {
        $this->assertFalse(new Action('back', 0, 'back')->isInput());
        $this->assertFalse(new Action('forward', 0, 'forward')->isInput());
        $this->assertFalse(new Action('reload', 0, 'reload')->isInput());
        $this->assertFalse(new Action('click $"s"', 0, 'click', '$"s"', '$"s"')->isInput());
        $this->assertFalse(new Action('submit $"s"', 0, 'submit', '$"s"', '$"s"')->isInput());
        $this->assertFalse(new Action('wait-for $"s"', 0, 'wait-for', '$"s"', '$"s"')->isInput());
        $this->assertTrue(new Action('set $"s" to "v"', 0, 'set', '$"s" to "v"', '$"s"', '"v"')->isInput());
        $this->assertFalse(new Action('wait 1', 0, 'wait', '1', null, '1')->isInput());
    }

    public function testIsWait(): void
    {
        $this->assertFalse(new Action('back', 0, 'back')->isWait());
        $this->assertFalse(new Action('forward', 0, 'forward')->isWait());
        $this->assertFalse(new Action('reload', 0, 'reload')->isWait());
        $this->assertFalse(new Action('click $"s"', 0, 'click', '$"s"', '$"s"')->isWait());
        $this->assertFalse(new Action('submit $"s"', 0, 'submit', '$"s"', '$"s"')->isWait());
        $this->assertFalse(new Action('wait-for $"s"', 0, 'wait-for', '$"s"', '$"s"')->isWait());
        $this->assertFalse(new Action('set $"s" to "v"', 0, 'set', '$"s" to "v"', '$"s"', '"v"')->isWait());
        $this->assertTrue(new Action('wait 1', 0, 'wait', '1', null, '1')->isWait());
    }
}
