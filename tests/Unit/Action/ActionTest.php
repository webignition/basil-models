<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;

class ActionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = 'click $".selector"';
        $type = 'click';
        $arguments = '$".selector"';

        $action = new Action($source, $type, $arguments);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($source, (string) $action);
        $this->assertSame($type, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
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
            'back' => [
                'action' => new Action(
                    'back',
                    'back',
                    ''
                ),
                'expectedSerializedData' => [
                    'source' => 'back',
                    'type' => 'back',
                    'arguments' => '',
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param ActionInterface $expectedAction
     */
    public function testFromArray(array $data, ?ActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, Action::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedAction' => new Action('', '', ''),
            ],
            'source missing' => [
                'data' => [
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('', 'back', ''),
            ],
            'type missing' => [
                'data' => [
                    'source' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('back', '', ''),
            ],
            'arguments missing' => [
                'data' => [
                    'source' => 'back',
                    'type' => 'back',
                ],
                'expectedAction' => new Action('back', 'back', ''),
            ],
            'source, type, arguments present' => [
                'data' => [
                    'source' => 'back',
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('back', 'back', ''),
            ],
        ];
    }
}
