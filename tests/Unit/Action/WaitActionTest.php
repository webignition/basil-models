<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\WaitAction;

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
}
