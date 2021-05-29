<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;

class ActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $source,
        string $type,
        ?string $arguments,
        ?string $identifier,
        ?string $value
    ): void {
        $action = new Action($source, $type, $arguments, $identifier, $value);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($type, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }

    /**
     * @return array[]
     */
    public function createDataProvider(): array
    {
        return [
            'action-only' => [
                'source' => 'back',
                'type' => 'back',
                'arguments' => null,
                'identifier' => null,
                'value' => null,
            ],
            'interaction' => [
                'source' => 'click $".selector"',
                'type' => 'click',
                'arguments' => '$".selector"',
                'identifier' => '$".selector"',
                'value' => null,
            ],
            'input' => [
                'source' => 'set $".selector" to "value"',
                'type' => 'set',
                'arguments' => '$".selector"',
                'identifier' => '$".selector"',
                'value' => '"value"',
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param array<string, string> $expectedSerializedData
     */
    public function testJsonSerialize(ActionInterface $action, array $expectedSerializedData): void
    {
        $serializedStatement = $action->jsonSerialize();

        ksort($serializedStatement);
        ksort($expectedSerializedData);

        self::assertSame($expectedSerializedData, $serializedStatement);
    }

    /**
     * @return array[]
     */
    public function jsonSerializeDataProvider(): array
    {
        return [
            'action-only' => [
                'action' => new Action('back', 'back'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'back',
                    'type' => 'back',
                ],
            ],
            'interaction' => [
                'action' => new Action('click $".selector"', 'click', '$".selector"', '$".selector"'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
            ],
            'input' => [
                'action' => new Action(
                    'set $".selector" to "value"',
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
                ],
            ],
            'wait' => [
                'action' => new Action('wait', 'wait', '30', null, '30'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'wait',
                    'type' => 'wait',
                    'arguments' => '30',
                    'value' => '30',
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
        $this->assertTrue((new Action('back', 'back'))->isBrowserOperation());
        $this->assertTrue((new Action('forward', 'forward'))->isBrowserOperation());
        $this->assertTrue((new Action('reload', 'reload'))->isBrowserOperation());
        $this->assertFalse((new Action('click $"s"', 'click', '$"s"', '$"s"'))->isBrowserOperation());
        $this->assertFalse((new Action('submit $"s"', 'submit', '$"s"', '$"s"'))->isBrowserOperation());
        $this->assertFalse((new Action('wait-for $"s"', 'wait-for', '$"s"', '$"s"'))->isBrowserOperation());
        $this->assertFalse((new Action('set $"s" to "v"', 'set', '$"s" to "v"', '$"s"', '"v"'))->isBrowserOperation());
        $this->assertFalse((new Action('wait 1', 'wait', '1', null, '1'))->isBrowserOperation());
    }

    public function testIsInteraction(): void
    {
        $this->assertFalse((new Action('back', 'back'))->isInteraction());
        $this->assertFalse((new Action('forward', 'forward'))->isInteraction());
        $this->assertFalse((new Action('reload', 'reload'))->isInteraction());
        $this->assertTrue((new Action('click $"s"', 'click', '$"s"', '$"s"'))->isInteraction());
        $this->assertTrue((new Action('submit $"s"', 'submit', '$"s"', '$"s"'))->isInteraction());
        $this->assertTrue((new Action('wait-for $"s"', 'wait-for', '$"s"', '$"s"'))->isInteraction());
        $this->assertFalse((new Action('set $"s" to "v"', 'set', '$"s" to "v"', '$"s"', '"v"'))->isInteraction());
        $this->assertFalse((new Action('wait 1', 'wait', '1', null, '1'))->isInteraction());
    }

    public function testIsInput(): void
    {
        $this->assertFalse((new Action('back', 'back'))->isInput());
        $this->assertFalse((new Action('forward', 'forward'))->isInput());
        $this->assertFalse((new Action('reload', 'reload'))->isInput());
        $this->assertFalse((new Action('click $"s"', 'click', '$"s"', '$"s"'))->isInput());
        $this->assertFalse((new Action('submit $"s"', 'submit', '$"s"', '$"s"'))->isInput());
        $this->assertFalse((new Action('wait-for $"s"', 'wait-for', '$"s"', '$"s"'))->isInput());
        $this->assertTrue((new Action('set $"s" to "v"', 'set', '$"s" to "v"', '$"s"', '"v"'))->isInput());
        $this->assertFalse((new Action('wait 1', 'wait', '1', null, '1'))->isInput());
    }

    public function testIsWait(): void
    {
        $this->assertFalse((new Action('back', 'back'))->isWait());
        $this->assertFalse((new Action('forward', 'forward'))->isWait());
        $this->assertFalse((new Action('reload', 'reload'))->isWait());
        $this->assertFalse((new Action('click $"s"', 'click', '$"s"', '$"s"'))->isWait());
        $this->assertFalse((new Action('submit $"s"', 'submit', '$"s"', '$"s"'))->isWait());
        $this->assertFalse((new Action('wait-for $"s"', 'wait-for', '$"s"', '$"s"'))->isWait());
        $this->assertFalse((new Action('set $"s" to "v"', 'set', '$"s" to "v"', '$"s"', '"v"'))->isWait());
        $this->assertTrue((new Action('wait 1', 'wait', '1', null, '1'))->isWait());
    }
}
