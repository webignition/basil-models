<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageUrlReference;

use webignition\BasilModels\AbstractObjectWithProperty;

class PageUrlReference extends AbstractObjectWithProperty implements PageUrlReferenceInterface
{
    protected static function getPatternPrefix(): string
    {
        return '\$[^\.]+';
    }

    protected static function getPropertyPattern(): string
    {
        return 'url';
    }

    public function getImportName(): string
    {
        return ltrim($this->getValuePart(0), '$');
    }
}
