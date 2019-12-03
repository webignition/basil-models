<?php

declare(strict_types=1);

namespace webignition\BasilModels\AttributeReference;

use webignition\BasilModels\ElementReference\ElementReference;

class AttributeReference implements AttributeReferenceInterface
{
    private const PART_DELIMITER = '.';

    private const REGEX = '/^\$elements\.[^\.]+\.[^\.]+$/';

    private $elementName = '';
    private $attributeName = '';
    private $isValid = false;
    private $reference  = '';

    public function __construct(string $reference)
    {
        $reference = trim($reference);
        $this->reference = $reference;

        if (self::is($reference)) {
            $referenceParts = explode(self::PART_DELIMITER, $reference);

            $this->attributeName = array_pop($referenceParts);

            $elementReference = new ElementReference(implode(self::PART_DELIMITER, $referenceParts));

            $this->elementName = $elementReference->getElementName();
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

    public function getAttributeName(): string
    {
        return $this->attributeName;
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
