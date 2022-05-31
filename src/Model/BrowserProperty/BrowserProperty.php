<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\BrowserProperty;

use webignition\BasilModels\Model\AbstractObjectWithProperty;

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
