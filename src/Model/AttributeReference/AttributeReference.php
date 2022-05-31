<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\AttributeReference;

use webignition\BasilModels\Model\AbstractObjectWithProperty;

class AttributeReference extends AbstractObjectWithProperty implements AttributeReferenceInterface
{
    public function getElementName(): string
    {
        return $this->getPropertyPart(0);
    }

    public function getAttributeName(): string
    {
        return $this->getPropertyPart(1);
    }

    protected static function getPatternPrefix(): string
    {
        return '\$elements';
    }

    protected static function getPropertyPattern(): string
    {
        return '[^\.]+\.[^\.]+';
    }
}
