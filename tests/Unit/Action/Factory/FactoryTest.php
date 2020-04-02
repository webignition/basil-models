<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action\Factory;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\Factory\Factory;
use webignition\BasilModels\Action\Factory\MalformedDataException;
use webignition\BasilModels\Action\InputAction;
use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Action\WaitAction;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    /**
     * @dataProvider createFromArrayDataProvider
     *
     * @param array<mixed> $actionData
     * @param ActionInterface $expectedAction
     */
    public function testCreateFromArray(array $actionData, ActionInterface $expectedAction)
    {
        $this->assertEquals($expectedAction, $this->factory->createFromArray($actionData));
    }

    public function createFromArrayDataProvider(): array
    {
        return [
            'back' => [
                'actionData' => [
                    'source' => 'back',
                    'type' => 'back',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('back', 'back', ''),
            ],
            'forward' => [
                'actionData' => [
                    'source' => 'forward',
                    'type' => 'forward',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('forward', 'forward', ''),
            ],
            'reload' => [
                'actionData' => [
                    'source' => 'reload',
                    'type' => 'reload',
                    'arguments' => '',
                ],
                'expectedAction' => new Action('reload', 'reload', ''),
            ],
            'click' => [
                'actionData' => [
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
            'submit' => [
                'actionData' => [
                    'source' => 'submit $".selector"',
                    'type' => 'submit',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction(
                    'submit $".selector"',
                    'submit',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'wait-for' => [
                'actionData' => [
                    'source' => 'wait-for $".selector"',
                    'type' => 'wait-for',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ],
                'expectedAction' => new InteractionAction(
                    'wait-for $".selector"',
                    'wait-for',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'set' => [
                'actionData' => [
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
            'wait' => [
                'actionData' => [
                    'source' => 'wait 30',
                    'type' => 'wait',
                    'arguments' => '30',
                    'duration' => '30',
                ],
                'expectedAction' => new WaitAction(
                    'wait 30',
                    '30'
                ),
            ],
        ];
    }

    /**
     * @dataProvider createFromArrayThrowsMalformedDataExceptionDataProvider
     *
     * @param array<mixed> $actionData
     */
    public function testCreateFromArrayThrowsMalformedDataException(array $actionData)
    {
        try {
            $this->factory->createFromArray($actionData);
            $this->fail('MalformedDataException not throw');
        } catch (MalformedDataException $malformedDataException) {
            $this->assertSame($actionData, $malformedDataException->getData());
        }
    }

    public function createFromArrayThrowsMalformedDataExceptionDataProvider(): array
    {
        return [
            'empty' => [
                'actionData' => [],
            ],
            'unknown type' => [
                'actionData' => [
                    'type' => 'foo',
                ],
            ],
            'malformed action (lacking source, arguments)' => [
                'actionData' => [
                    'type' => 'back',
                ],
            ],
            'malformed interaction action (lacking source, arguments)' => [
                'actionData' => [
                    'type' => 'click',
                ],
            ],
            'malformed interaction action (lacking identifier)' => [
                'actionData' => [
                    'source' => 'click $".selector"',
                    'type' => 'click',
                    'arguments' => '$".selector"',
                ],
            ],
            'malformed input action (lacking value)' => [
                'actionData' => [
                    'source' => 'set $".selector" to "value"',
                    'type' => 'set',
                    'arguments' => '$".selector" to "value"',
                    'identifier' => '$".selector"',
                ],
            ],
            'malformed wait action (lacking duration)' => [
                'actionData' => [
                    'source' => 'wait 30',
                    'type' => 'wait',
                    'arguments' => '30',
                ],
            ],
        ];
    }
}