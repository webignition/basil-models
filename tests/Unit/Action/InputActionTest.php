<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\InputAction;
use webignition\BasilModels\Action\InputActionInterface;

class InputActionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = 'set $".selector" to "value"';
        $arguments = '$".selector" to "value"';
        $identifier = '$".selector"';
        $value = '"value"';

        $action = new InputAction($source, $arguments, $identifier, $value);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($source, (string) $action);
        $this->assertSame(InputAction::TYPE, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }

    public function testWithIdentifier()
    {
        $originalIdentifier = '$elements.element_name';
        $newIdentifier = '.selector';

        $action = new InputAction(
            'set $elements.element_name to "value"',
            '$elements.element_name to "value"',
            '$elements.element_name',
            '"value"'
        );
        $mutatedAction = $action->withIdentifier($newIdentifier);

        $this->assertNotSame($action, $mutatedAction);
        $this->assertSame($originalIdentifier, $action->getIdentifier());
        $this->assertSame($newIdentifier, $mutatedAction->getIdentifier());
    }

    public function testWithValue()
    {
        $originalValue = '$elements.element_name';
        $newValue = '.value';

        $action = new InputAction(
            'set $".selector" to $elements.element_name',
            '$".selector" to $elements.element_name',
            '$".selector"',
            '$elements.element_name'
        );
        $mutatedAction = $action->withValue($newValue);

        $this->assertNotSame($action, $mutatedAction);
        $this->assertSame($originalValue, $action->getValue());
        $this->assertSame($newValue, $mutatedAction->getValue());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param ActionInterface $action
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(ActionInterface $action, array $expectedSerializedData)
    {
        $this->assertEquals($expectedSerializedData, $action->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'set' => [
                'action' => new InputAction(
                    'set $".selector" to "value"',
                    '$".selector" to "value"',
                    '$".selector"',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector" to "value"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param InputActionInterface $expectedAction
     */
    public function testFromArray(array $data, ?InputActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, InputAction::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedAction' => null,
            ],
            'source missing' => [
                'data' => [
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => null,
            ],
            'type missing' => [
                'data' => [
                    'source' => 'set $".selector" to "value"',
                        'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => null,
            ],
            'arguments missing' => [
                'data' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => null,
            ],
            'identifier missing' => [
                'data' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => null,
            ],
            'value missing' => [
                'data' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => null,
            ],
            'source, type, arguments, identifier present' => [
                'data' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ],
                'expectedAction' => new InputAction(
                    'set $".selector" to "value"',
                    '$".selector"',
                    '$".selector"',
                    '"value"'
                ),
            ],
        ];
    }
}
