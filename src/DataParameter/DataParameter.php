<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataParameter;

use webignition\BasilModels\AbstractObjectWithProperty;

class DataParameter extends AbstractObjectWithProperty implements DataParameterInterface
{
    protected static function getPatternPrefix(): string
    {
        return '\$data';
    }

    protected static function getPropertyPattern(): string
    {
        return '[^\.]+';
    }
}
