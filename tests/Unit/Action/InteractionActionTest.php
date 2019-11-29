<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\InteractionAction;

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
        $this->assertSame($type, $action->getType());
        $this->assertSame($arguments, $action->getArguments());
        $this->assertSame($identifier, $action->getIdentifier());
    }
}
