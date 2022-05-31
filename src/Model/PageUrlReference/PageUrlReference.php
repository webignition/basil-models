<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\PageUrlReference;

use webignition\BasilModels\Model\AbstractObjectWithProperty;

class PageUrlReference extends AbstractObjectWithProperty implements PageUrlReferenceInterface
{
    public function getImportName(): string
    {
        return ltrim($this->getValuePart(0), '$');
    }

    protected static function getPatternPrefix(): string
    {
        return '\$[^\.]+';
    }

    protected static function getPropertyPattern(): string
    {
        return 'url';
    }
}
