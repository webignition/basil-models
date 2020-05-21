<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Action\InteractionActionInterface;

class InteractionActionTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $source = 'click ".selector"';
        $type = 'click';
        $arguments = '".selector"';
        $identifier = '.selector';

        $action = new InteractionAction($source, $type, $arguments, $identifier);

        $this->assertSame($source, $action->getSource());
        $this->assertSame($source, (string) $action);
        $this->assertSame($type, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
    }

    public function testWithIdentifier()
    {
        $originalIdentifier = '$elements.element_name';
        $newIdentifier = '.selector';

        $action = new InteractionAction(
            'click $elements.element_name',
            'click',
            '$elements.element_name',
            '$elements.element_name'
        );
        $mutatedAction = $action->withIdentifier($newIdentifier);

        $this->assertNotSame($action, $mutatedAction);
        $this->assertSame($originalIdentifier, $action->getIdentifier());
        $this->assertSame($newIdentifier, $mutatedAction->getIdentifier());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param InteractionActionInterface $action
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(InteractionActionInterface $action, array $expectedSerializedData)
    {
        $this->assertEquals($expectedSerializedData, $action->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'click' => [
                'action' => new InteractionAction(
                    'click $".selector"',
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param InteractionActionInterface $expectedAction
     */
    public function testFromArray(array $data, ?InteractionActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, InteractionAction::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedAction' => new InteractionAction('', '', '', ''),
            ],
            'source missing' => [
                'data' => [
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction('', 'click', '$".selector"', '$".selector"'),
            ],
            'type missing' => [
                'data' => [
                    'source' => 'click $".selector"',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction('click $".selector"', '', '$".selector"', '$".selector"'),
            ],
            'arguments missing' => [
                'data' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction('click $".selector"', 'click', '', '$".selector"'),
            ],
            'identifier missing' => [
                'data' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction('click $".selector"', 'click', '$".selector"', ''),
            ],
            'source, type, arguments, identifier present' => [
                'data' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction(
                    'click $".selector"',
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
        ];
    }
}
