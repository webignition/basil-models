<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Page;

use webignition\BasilModels\Page\Page;

class PageTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $emptyPage = new Page('', []);
        $this->assertSame('', $emptyPage->getUrl());
        $this->assertSame([], $emptyPage->getIdentifiers());

        $nonEmptyPage = new Page('http://example.com', ['title' => '.title']);

        $this->assertSame('http://example.com', $nonEmptyPage->getUrl());
        $this->assertSame(['title' => '.title'], $nonEmptyPage->getIdentifiers());
    }

    public function testGetIdentifier()
    {
        $headingIdentifier = '$".heading"';
        $headingIdentifierName = 'heading';

        $page = new Page('', [
            $headingIdentifierName => $headingIdentifier,
        ]);

        $this->assertSame($headingIdentifier, $page->getIdentifier($headingIdentifierName));
        $this->assertNull($page->getIdentifier('non-existent'));
    }
}
