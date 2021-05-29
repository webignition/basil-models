<?php

declare(strict_types=1);

namespace webignition\BasilModels\ElementReference;

use webignition\BasilModels\AbstractObjectWithProperty;

class ElementReference extends AbstractObjectWithProperty implements ElementReferenceInterface
{
    public function getElementName(): string
    {
        return $this->getProperty();
    }

    protected static function getPatternPrefix(): string
    {
        return '\$elements';
    }

    protected static function getPropertyPattern(): string
    {
        return '[^\.]+';
    }

    protected function getPropertyIndex(): int
    {
        return 1;
    }
}
