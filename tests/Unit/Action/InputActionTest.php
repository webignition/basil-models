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
        $this->assertSame(InputAction::TYPE, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
        $this->assertSame($value, $action->getValue());
    }
}
