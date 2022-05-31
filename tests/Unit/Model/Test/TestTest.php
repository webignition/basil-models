<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Test;

use webignition\BasilModels\Model\Step\StepCollection;
use webignition\BasilModels\Model\Test\Configuration;
use webignition\BasilModels\Model\Test\Test;

class TestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetPath(): void
    {
        $test = new Test(new Configuration('chrome', 'http://example.com/'), new StepCollection([]));
        $this->assertNull($test->getPath());

        $path = 'test.yml';
        $test = $test->withPath($path);
        $this->assertSame($path, $test->getPath());
    }

    public function testGetConfiguration(): void
    {
        $configuration = new Configuration('chrome', 'http://example.com/');
        $test = new Test($configuration, new StepCollection([]));
        $this->assertSame($configuration, $test->getConfiguration());
    }

    public function testGetSteps(): void
    {
        $steps = new StepCollection([]);
        $test = new Test(new Configuration('chrome', 'http://example.com/'), $steps);

        $this->assertSame($steps, $test->getSteps());
    }
}
