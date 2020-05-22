<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\FooAction;
use webignition\BasilModels\Action\FooActionInterface;

class FooActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, string $type, ?string $arguments, ?string $identifier, ?string $value)
    {
        $action = new FooAction($source, $type, $arguments, $identifier, $value);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($type, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }

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
     * @param FooActionInterface $action
     * @param array<string, string> $expectedSerializedData
     */
    public function testJsonSerialize(FooActionInterface $action, array $expectedSerializedData)
    {
        $this->assertSame($expectedSerializedData, $action->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'action-only' => [
                'action' => new FooAction('back', 'back'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'back',
                    'type' => 'back',
                ],
            ],
            'interaction' => [
                'action' => new FooAction('click $".selector"', 'click', '$".selector"', '$".selector"'),
                'expectedSerializedData' => [
                    'statement-type' => 'action',
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
            ],
            'input' => [
                'action' => new FooAction(
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
        ];
    }
}
