<?php

declare(strict_types=1);

namespace webignition\BasilModels\BrowserProperty;

use webignition\BasilModels\AbstractObjectWithProperty;

class BrowserProperty extends AbstractObjectWithProperty implements BrowserPropertyInterface
{
    protected static function getPatternPrefix(): string
    {
        return '\$browser';
    }

    protected static function getPropertyPattern(): string
    {
        return 'size';
    }
}
