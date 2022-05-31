<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Provider\Page;

use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\Page\EmptyPageProvider;

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
