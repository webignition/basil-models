<?php

declare(strict_types=1);

namespace webignition\BasilModels\AttributeReference;

use webignition\BasilModels\AbstractObjectWithProperty;

class AttributeReference extends AbstractObjectWithProperty implements AttributeReferenceInterface
{
    protected static function getPropertyPattern(): string
    {
        return '[^\.]+\.[^\.]+';
    }

    public function getElementName(): string
    {
        return $this->getPropertyPart(0);
    }

    public function getAttributeName(): string
    {
        return $this->getPropertyPart(1);
    }

    private function getPropertyPart(int $partIndex): string
    {
        if (false === $this->isValid()) {
            return '';
        }

        $propertyParts = explode(self::PART_DELIMITER, $this->getProperty());

        return $propertyParts[$partIndex];
    }

    protected static function getPatternPrefix(): string
    {
        return 'elements';
    }
}
