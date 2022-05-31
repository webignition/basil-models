<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\PageProperty;

use webignition\BasilModels\Model\AbstractObjectWithProperty;

class PageProperty extends AbstractObjectWithProperty implements PagePropertyInterface
{
    protected static function getPatternPrefix(): string
    {
        return '\$page';
    }

    protected static function getPropertyPattern(): string
    {
        return '(title|url)';
    }
}
