<?php

declare(strict_types=1);

namespace webignition\BasilModels\ElementReference;

class ElementReference implements ElementReferenceInterface
{
    private const PART_DELIMITER = '.';
    private const EXPECTED_PART_COUNT = 2;
    private const EXPECTED_ELEMENTS_PART = '$elements';

    private const ELEMENTS_PART_INDEX = 0;
    private const ELEMENT_NAME_INDEX = 1;

    private const REGEX = '/^\$elements\.[^\.]+$/';

    private $elementName = '';
    private $isValid = false;
    private $reference  = '';

    public function __construct(string $reference)
    {
        $reference = trim($reference);
        $this->reference = $reference;

        $referenceParts = explode(self::PART_DELIMITER, $reference);

        $hasExpectedPartCount = self::EXPECTED_PART_COUNT === count($referenceParts);

        if ($hasExpectedPartCount && self::EXPECTED_ELEMENTS_PART === $referenceParts[self::ELEMENTS_PART_INDEX]) {
            $this->elementName = $referenceParts[self::ELEMENT_NAME_INDEX];
            $this->isValid = true;
        }
    }

    public static function is(string $elementReference): bool
    {
        return preg_match(self::REGEX, $elementReference) > 0;
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
