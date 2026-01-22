<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Statement\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Statement\Action\Factory as ActionFactory;
use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Statement\Assertion\Factory;
use webignition\BasilModels\Tests\DataProvider\CreateAssertionDataProviderTrait;

class FactoryTest extends TestCase
{
    use CreateAssertionDataProviderTrait;

    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(
            ActionFactory::createFactory()
        );
    }

    #[DataProvider('createAssertionDataProvider')]
    public function testCreateFromArray(string $json, AssertionInterface $expected): void
    {
        $data = json_decode($json, true);
        \assert(is_array($data));

        $this->assertEquals($expected, $this->factory->createFromArray($data));
    }

    #[DataProvider('createAssertionDataProvider')]
    public function testCreateFromJson(string $json, AssertionInterface $expected): void
    {
        $this->assertEquals($expected, $this->factory->createFromJson($json));
    }
}
