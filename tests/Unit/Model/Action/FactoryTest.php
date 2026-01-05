<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Action;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Action\Factory;
use webignition\BasilModels\Tests\DataProvider\CreateActionDataProviderTrait;

class FactoryTest extends TestCase
{
    use CreateActionDataProviderTrait;

    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    #[DataProvider('createActionDataProvider')]
    public function testCreateFromArray(string $json, ActionInterface $expected): void
    {
        $data = json_decode($json, true);
        \assert(is_array($data));

        $this->assertEquals($expected, $this->factory->createFromArray($data));
    }

    #[DataProvider('createActionDataProvider')]
    public function testCreateFromJson(string $json, ActionInterface $expected): void
    {
        $this->assertEquals($expected, $this->factory->createFromJson($json));
    }
}
