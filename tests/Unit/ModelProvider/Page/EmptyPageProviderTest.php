<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\ModelProvider\Page;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\Page\EmptyPageProvider;

class EmptyPageProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testFindThrowsUnknownItemException(): void
    {
        $this->expectException(UnknownItemException::class);
        $this->expectExceptionMessage('Unknown page "page_import_name"');

        $provider = new EmptyPageProvider();
        $provider->find('page_import_name');
    }
}
