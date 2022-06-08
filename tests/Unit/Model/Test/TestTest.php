<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Test;

use webignition\BasilModels\Model\Step\StepCollection;
use webignition\BasilModels\Model\Test\Configuration;
use webignition\BasilModels\Model\Test\Test;

class TestTest extends \PHPUnit\Framework\TestCase
{
    private Configuration $configuration;
    private StepCollection $stepCollection;
    private Test $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new Configuration(md5((string) rand()), md5((string) rand()));
        $this->stepCollection = new StepCollection([]);

        $this->test = new Test($this->configuration, $this->stepCollection);
    }

    public function testGetConfiguration(): void
    {
        self::assertSame($this->configuration, $this->test->getConfiguration());
    }

    public function testGetSteps(): void
    {
        self::assertSame($this->stepCollection, $this->test->getSteps());
    }
}
