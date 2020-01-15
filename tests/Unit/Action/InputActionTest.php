<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\InputAction;

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
}
