<?php

declare(strict_types=1);

namespace webignition\BasilModels\BrowserProperty;

class BrowserProperty implements BrowserPropertyInterface
{
    private const PART_DELIMITER = '.';
    private const REGEX = '/^\$browser\.(size)$/';

    private const PROPERTY_INDEX = 1;

    private $property = '';
    private $isValid = false;
    private $value  = '';

    public function __construct(string $value)
    {
        $value = trim($value);
        $this->value = $value;

        if (self::is($value)) {
            $referenceParts = explode(self::PART_DELIMITER, $value);
            $this->property = $referenceParts[self::PROPERTY_INDEX];
            $this->isValid = true;
        }
    }

    public static function is(string $value): bool
    {
        return preg_match(self::REGEX, $value) > 0;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
