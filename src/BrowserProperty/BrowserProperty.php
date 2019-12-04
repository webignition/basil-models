<?php

declare(strict_types=1);

namespace webignition\BasilModels\BrowserProperty;

use webignition\BasilModels\AbstractObjectWithProperty;

class BrowserProperty extends AbstractObjectWithProperty implements BrowserPropertyInterface
{
    protected static function getObjectName(): string
    {
        return 'browser';
    }

    protected function getPropertyIndex(): int
    {
        return 1;
    }

    protected static function getPropertyPattern(): string
    {
        return 'size';
    }
}
