<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageElementReference;

use webignition\BasilModels\ElementReference\ElementReference;

class PageElementReference implements PageElementReferenceInterface
{
    private const PART_DELIMITER = '.';

    private $importName = '';
    private $elementName = '';
    private $isValid = false;
    private $reference  = '';

    private const REGEX = '/^[^\.]+\.elements\.[^.]+$/';

    public function __construct(string $reference)
    {
        $reference = trim($reference);
        $this->reference = $reference;

        if (self::is($reference)) {
            $referenceParts = explode(self::PART_DELIMITER, $reference);
            $importName = array_shift($referenceParts);

            $elementReference = new ElementReference('$' . implode($referenceParts, '.'));

            $this->importName = $importName;
            $this->elementName = $elementReference->getElementName();
            $this->isValid = true;
        }
    }

    public static function is(string $pageElementReference): bool
    {
        return preg_match(self::REGEX, $pageElementReference) > 0;
    }


    public function getImportName(): string
    {
        return $this->importName;
    }

    public function getElementName(): string
    {
        return $this->elementName;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function __toString(): string
    {
        return $this->reference;
    }
}
