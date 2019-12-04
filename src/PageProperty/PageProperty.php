<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageProperty;

use webignition\BasilModels\AbstractObjectWithProperty;

class PageProperty extends AbstractObjectWithProperty implements PagePropertyInterface
{
    protected static function getObjectName(): string
    {
        return 'page';
    }

    protected function getPropertyIndex(): int
    {
        return 1;
    }

    protected static function getPropertyPattern(): string
    {
        return 'title|url';
    }
}
