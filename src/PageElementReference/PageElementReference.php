<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageElementReference;

use webignition\BasilModels\ElementReference\ElementReference;

class PageElementReference implements PageElementReferenceInterface
{
    private const PART_DELIMITER = '.';
    private const EXPECTED_PART_COUNT = 3;

    private $importName = '';
    private $elementName = '';
    private $isValid = false;
    private $reference  = '';

    private const REGEX = '/^[^\.]+\.elements\.[^.]+$/';

    public function __construct(string $reference)
    {
        $reference = trim($reference);
        $this->reference = $reference;

        $referenceParts = explode(self::PART_DELIMITER, $reference);

        $hasExpectedPartCount = self::EXPECTED_PART_COUNT === count($referenceParts);

        if ($hasExpectedPartCount) {
            $elementReferenceParts = $referenceParts;
            $importName = array_shift($elementReferenceParts);

            $elementReference = new ElementReference('$' . implode($elementReferenceParts, '.'));

            if ($elementReference->isValid()) {
                $this->importName = $importName;
                $this->elementName = $elementReference->getElementName();
                $this->isValid = true;
            }
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
