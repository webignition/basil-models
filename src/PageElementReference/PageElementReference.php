<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageElementReference;

use webignition\BasilModels\AbstractObjectWithProperty;

class PageElementReference extends AbstractObjectWithProperty implements PageElementReferenceInterface
{
    public function getImportName(): string
    {
        return $this->getValuePart(0);
    }

    public function getElementName(): string
    {
        return $this->getValuePart(2);
    }

    public function getAttributeName(): string
    {
        return $this->getValuePart(3);
    }

    protected static function getPatternPrefix(): string
    {
        return '[^\.]+';
    }

    protected static function getPropertyPattern(): string
    {
        return 'elements\.[^ ]+';
    }

    protected function getMaxPartCount(): int
    {
        return 4;
    }
}
