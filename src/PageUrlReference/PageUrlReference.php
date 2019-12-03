<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageUrlReference;

class PageUrlReference implements PageUrlReferenceInterface
{
    private const PART_DELIMITER = '.';
    private const REGEX = '/^[^\.]+\.url$/';

    private const IMPORT_NAME_INDEX = 0;

    private $importName = '';
    private $isValid = false;
    private $reference  = '';

    public function __construct(string $reference)
    {
        $reference = trim($reference);
        $this->reference = $reference;

        if (self::is($reference)) {
            $referenceParts = explode(self::PART_DELIMITER, $reference);
            $this->importName = $referenceParts[self::IMPORT_NAME_INDEX];
            $this->isValid = true;
        }
    }

    public static function is(string $pageUrlReference): bool
    {
        return preg_match(self::REGEX, $pageUrlReference) > 0;
    }

    public function getImportName(): string
    {
        return $this->importName;
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
