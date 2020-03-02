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
}
