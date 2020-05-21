<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\WaitAction;
use webignition\BasilModels\Action\WaitActionInterface;

class WaitActionTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $source = 'wait 30';
        $duration = '30';

        $action = new WaitAction($source, $duration);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($source, (string) $action);
        $this->assertSame(WaitAction::TYPE, $action->getType());
        $this->assertSame($duration, $action->getArguments());
        $this->assertSame($duration, $action->getDuration());
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
            'wait' => [
                'action' => new WaitAction(
                    'wait 30',
                    '30'
                ),
                'expectedSerializedData' => [
                    'source' => 'wait 30',
                    'type' => 'wait',
                    'arguments' => '30',
                    'duration' => '30',
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param WaitActionInterface $expectedAction
     */
    public function testFromArray(array $data, ?WaitActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, WaitAction::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedAction' => new WaitAction('', ''),
            ],
            'source missing' => [
                'data' => [
                    'type' => 'wait',
                    'arguments' => '1',
                    'duration' => '1',
                ],
                'expectedAction' => new WaitAction('', '1'),
            ],
            'type missing' => [
                'data' => [
                    'source' => 'wait 1',
                    'arguments' => '1',
                    'duration' => '1',
                ],
                'expectedAction' => new WaitAction('wait 1', '1'),
            ],
            'arguments missing' => [
                'data' => [
                    'source' => 'wait 1',
                    'type' => 'wait',
                    'duration' => '1',
                ],
                'expectedAction' => new WaitAction('wait 1', '1'),
            ],
            'duration missing' => [
                'data' => [
                    'source' => 'wait 1',
                    'type' => 'wait',
                    'arguments' => '1',
                ],
                'expectedAction' => new WaitAction('wait 1', ''),
            ],
            'source, type, arguments, duration present' => [
                'data' => [
                    'source' => 'wait 1',
                    'type' => 'wait',
                    'arguments' => '1',
                    'duration' => '1',
                ],
                'expectedAction' => new WaitAction('wait 1', '1'),
            ],
        ];
    }
}
