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

    /**
     * @return string[]
     */
    protected static function getProperties(): array
    {
        return [
            'size',
        ];
    }

    protected function getPropertyIndex(): int
    {
        return 1;
    }
}
