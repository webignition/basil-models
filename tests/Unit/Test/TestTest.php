<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Test;

use webignition\BasilModels\Step\StepCollection;
use webignition\BasilModels\Test\Configuration;
use webignition\BasilModels\Test\Test;

class TestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetPath()
    {
        $test = new Test(new Configuration('chrome', 'http://example.com/'), new StepCollection([]));
        $this->assertNull($test->getPath());

        $path = 'test.yml';
        $test = $test->withPath($path);
        $this->assertSame($path, $test->getPath());
    }

    public function testGetConfiguration()
    {
        $configuration = new Configuration('chrome', 'http://example.com/');
        $test = new Test($configuration, new StepCollection([]));
        $this->assertSame($configuration, $test->getConfiguration());
    }

    public function testGetSteps()
    {
        $steps = new StepCollection([]);
        $test = new Test(new Configuration('chrome', 'http://example.com/'), $steps);

        $this->assertSame($steps, $test->getSteps());
    }
}
