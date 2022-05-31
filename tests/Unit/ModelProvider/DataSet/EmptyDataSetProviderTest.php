<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\ModelProvider\DataSet;

use webignition\BasilModels\ModelProvider\DataSet\EmptyDataSetProvider;
use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;

class EmptyDataSetProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testFindThrowsUnknownItemException(): void
    {
        $this->expectException(UnknownItemException::class);
        $this->expectExceptionMessage('Unknown dataset "data_provider_import_name"');

        $provider = new EmptyDataSetProvider();
        $provider->find('data_provider_import_name');
    }
}
