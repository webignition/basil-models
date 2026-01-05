<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\StatementInterface;
use webignition\BasilModels\Tests\DataProvider\CreateActionDataProviderTrait;
use webignition\BasilModels\Tests\DataProvider\CreateAssertionDataProviderTrait;

class StatementFactoryTest extends TestCase
{
    use CreateActionDataProviderTrait;
    use CreateAssertionDataProviderTrait;

    private StatementFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = StatementFactory::createFactory();
    }

    #[DataProvider('createActionDataProvider')]
    #[DataProvider('createAssertionDataProvider')]
    public function testCreateFromJson(string $json, StatementInterface $expected): void
    {
        $this->assertEquals($expected, $this->factory->createFromJson($json));
    }
}
