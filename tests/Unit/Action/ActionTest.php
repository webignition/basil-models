<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Action;

use webignition\BasilModels\Action\Action;

class ActionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = 'click $".selector"';
        $type = 'click';
        $arguments = '$".selector"';

        $action = new Action($source, $type, $arguments);

        $this->assertEquals($source, $action->getSource());
        $this->assertEquals($type, $action->getType());
        $this->assertEquals($arguments, $action->getArguments());
    }
}
