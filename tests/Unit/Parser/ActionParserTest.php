<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Parser\ActionParser;
use webignition\BasilModels\Parser\Exception\UnparseableActionException;

class ActionParserTest extends TestCase
{
    private ActionParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = ActionParser::create();
    }

    #[DataProvider('parseDataProvider')]
    public function testParse(string $actionString, int $index, ActionInterface $expectedAction): void
    {
        $this->assertEquals($expectedAction, $this->parser->parse($actionString, $index));
    }

    /**
     * @return array<mixed>
     */
    public static function parseDataProvider(): array
    {
        return [
            'unknown type' => [
                'actionString' => 'foo $".selector"',
                'index' => 0,
                'expectedAction' => new Action('foo $".selector"', 0, 'foo', '$".selector"'),
            ],
            'click, index=0' => [
                'actionString' => 'click $".selector"',
                'index' => 0,
                'expectedAction' => new Action(
                    'click $".selector"',
                    0,
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'click, index=67' => [
                'actionString' => 'click $".selector"',
                'index' => 67,
                'expectedAction' => new Action(
                    'click $".selector"',
                    67,
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'click: parent > child' => [
                'actionString' => 'click $".parent" >> $".child"',
                'index' => 0,
                'expectedAction' => new Action(
                    'click $".parent" >> $".child"',
                    0,
                    'click',
                    '$".parent" >> $".child"',
                    '$".parent" >> $".child"'
                ),
            ],
            'click: grandparent > parent > child' => [
                'actionString' => 'click $".grandparent" >> $".parent" >> $".child"',
                'index' => 0,
                'expectedAction' => new Action(
                    'click $".grandparent" >> $".parent" >> $".child"',
                    0,
                    'click',
                    '$".grandparent" >> $".parent" >> $".child"',
                    '$".grandparent" >> $".parent" >> $".child"'
                ),
            ],
            'submit' => [
                'actionString' => 'submit $".selector"',
                'index' => 0,
                'expectedAction' => new Action(
                    'submit $".selector"',
                    0,
                    'submit',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'wait' => [
                'actionString' => 'wait 1',
                'index' => 0,
                'expectedAction' => new Action('wait 1', 0, 'wait', '1', null, '1'),
            ],
            'wait-for' => [
                'actionString' => 'wait-for $".selector"',
                'index' => 0,
                'expectedAction' => new Action(
                    'wait-for $".selector"',
                    0,
                    'wait-for',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'reload' => [
                'actionString' => 'reload',
                'index' => 0,
                'expectedAction' => new Action('reload', 0, 'reload'),
            ],
            'back' => [
                'actionString' => 'back',
                'index' => 0,
                'expectedAction' => new Action('back', 0, 'back'),
            ],
            'forward' => [
                'actionString' => 'forward',
                'index' => 0,
                'expectedAction' => new Action('forward', 0, 'forward'),
            ],
            'set to literal value, non-empty' => [
                'actionString' => 'set $".selector" to "value"',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".selector" to "value"',
                    0,
                    'set',
                    '$".selector" to "value"',
                    '$".selector"',
                    '"value"'
                ),
            ],
            'set to literal value, empty' => [
                'actionString' => 'set $".selector" to ""',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".selector" to ""',
                    0,
                    'set',
                    '$".selector" to ""',
                    '$".selector"',
                    '""'
                ),
            ],
            'set to variable value, data parameter' => [
                'actionString' => 'set $".selector" to $data.value',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".selector" to $data.value',
                    0,
                    'set',
                    '$".selector" to $data.value',
                    '$".selector"',
                    '$data.value'
                ),
            ],
            'set to variable value, dom identifier value (1)' => [
                'actionString' => 'set $".selector1" to $".selector2"',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".selector1" to $".selector2"',
                    0,
                    'set',
                    '$".selector1" to $".selector2"',
                    '$".selector1"',
                    '$".selector2"'
                ),
            ],
            'set to variable value, dom identifier value (2)' => [
                'actionString' => 'set $".selector1":1 to $".selector2":1',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".selector1":1 to $".selector2":1',
                    0,
                    'set',
                    '$".selector1":1 to $".selector2":1',
                    '$".selector1":1',
                    '$".selector2":1'
                ),
            ],
            'set to variable value, dom identifier value (3)' => [
                'actionString' => 'set $".parent1 .child1" to $".parent2 .child2"',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".parent1 .child1" to $".parent2 .child2"',
                    0,
                    'set',
                    '$".parent1 .child1" to $".parent2 .child2"',
                    '$".parent1 .child1"',
                    '$".parent2 .child2"'
                ),
            ],
            'set to variable value, dom identifier value (4)' => [
                'actionString' => 'set $".parent1" >> $".child1" to $".parent2" >> $".child2"',
                'index' => 0,
                'expectedAction' => new Action(
                    'set $".parent1" >> $".child1" to $".parent2" >> $".child2"',
                    0,
                    'set',
                    '$".parent1" >> $".child1" to $".parent2" >> $".child2"',
                    '$".parent1" >> $".child1"',
                    '$".parent2" >> $".child2"'
                ),
            ],
        ];
    }

    public function testParseEmptyAction(): void
    {
        $this->expectExceptionObject(UnparseableActionException::createEmptyActionException());

        $this->parser->parse('', 0);
    }

    #[DataProvider('parseInputActionEmptyValueDataProvider')]
    public function testParseInputActionEmptyValue(string $action, UnparseableActionException $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        $this->parser->parse($action, 0);
    }

    /**
     * @return array<mixed>
     */
    public static function parseInputActionEmptyValueDataProvider(): array
    {
        return [
            'set with "to" keyword lacking value' => [
                'action' => 'set $".selector" to',
                'expectedException' => UnparseableActionException::createEmptyInputActionValueException(
                    'set $".selector" to'
                ),
            ],
            'set lacking "to" keyword, lacking value' => [
                'action' => 'set $".selector"',
                'expectedException' => UnparseableActionException::createEmptyInputActionValueException(
                    'set $".selector"'
                ),
            ],
        ];
    }

    #[DataProvider('parseActionWithInvalidIdentifierDataProvider')]
    public function testParseActionWithInvalidIdentifier(string $action, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        $this->parser->parse($action, 0);
    }

    /**
     * @return array<mixed>
     */
    public static function parseActionWithInvalidIdentifierDataProvider(): array
    {
        return [
            'click action with non-dollar-prefixed selector' => [
                'action' => 'click "selector"',
                'expectedException' => UnparseableActionException::createInvalidIdentifierException('click "selector"'),
            ],
            'set action with non-dollar-prefixed selector' => [
                'action' => 'set "selector" to "value"',
                'expectedException' => UnparseableActionException::createInvalidIdentifierException(
                    'set "selector" to "value"'
                ),
            ],
        ];
    }
}
