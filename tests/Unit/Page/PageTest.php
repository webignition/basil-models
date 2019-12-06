<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Page;

use webignition\BasilModels\Page\Page;

class PageTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $emptyPage = new Page('', '', []);

        $this->assertSame('', $emptyPage->getImportName());
        $this->assertSame('', $emptyPage->getUrl());
        $this->assertSame([], $emptyPage->getIdentifiers());

        $nonEmptyPage = new Page('import_name', 'http://example.com', ['title' => '.title']);

        $this->assertSame('import_name', $nonEmptyPage->getImportName());
        $this->assertSame('http://example.com', $nonEmptyPage->getUrl());
        $this->assertSame(['title' => '.title'], $nonEmptyPage->getIdentifiers());
    }

    public function testGetIdentifier()
    {
        $headingIdentifier = '$".heading"';
        $headingIdentifierName = 'heading';

        $page = new Page('import_name', '', [
            $headingIdentifierName => $headingIdentifier,
        ]);

        $this->assertSame($headingIdentifier, $page->getIdentifier($headingIdentifierName));
        $this->assertNull($page->getIdentifier('non-existent'));
    }
}
