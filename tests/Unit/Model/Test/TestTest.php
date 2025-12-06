<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Test;

use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Step\StepCollection;
use webignition\BasilModels\Model\Test\Test;

class TestTest extends TestCase
{
    private string $browser;
    private string $url;
    private StepCollection $stepCollection;
    private Test $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->browser = md5((string) rand());
        $this->url = md5((string) rand());
        $this->stepCollection = new StepCollection([]);

        $this->test = new Test($this->browser, $this->url, $this->stepCollection);
    }

    public function testGetBrowser(): void
    {
        self::assertSame($this->browser, $this->test->getBrowser());
    }

    public function testGetUrl(): void
    {
        self::assertSame($this->url, $this->test->getUrl());
    }

    public function testGetSteps(): void
    {
        self::assertSame($this->stepCollection, $this->test->getSteps());
    }
}
