<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\InvalidStatementDataException;
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
    public function testCreateFromArray(string $json, StatementInterface $expected): void
    {
        $data = json_decode($json, true);
        \assert(is_array($data));

        $this->assertEquals($expected, $this->factory->createFromArray($data));
    }

    #[DataProvider('createActionDataProvider')]
    #[DataProvider('createAssertionDataProvider')]
    public function testCreateFromJson(string $json, StatementInterface $expected): void
    {
        $this->assertEquals($expected, $this->factory->createFromJson($json));
    }

    public function testCreateFromJsonNonArrayJson(): void
    {
        $exception = null;

        try {
            $this->factory->createFromJson('not an array');
        } catch (InvalidStatementDataException $exception) {
        }

        $this->assertInstanceOf(InvalidStatementDataException::class, $exception);
        $this->assertSame('not an array', $exception->statementJson);
    }

    public function testCreateFromJsonInvalidData(): void
    {
        $exception = null;

        try {
            $this->factory->createFromJson('{}');
        } catch (InvalidStatementDataException $exception) {
        }

        $this->assertInstanceOf(InvalidStatementDataException::class, $exception);
        $this->assertSame('{}', $exception->statementJson);
    }
}
