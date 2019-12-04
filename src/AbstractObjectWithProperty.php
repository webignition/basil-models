<?php

declare(strict_types=1);

namespace webignition\BasilModels;

abstract class AbstractObjectWithProperty
{
    private const PART_DELIMITER = '.';
    private const PATTERN_DELIMITER = '/';

    private $value  = '';
    private $property = '';
    private $isValid = false;

    public function __construct(string $value)
    {
        $value = trim($value);
        $this->value = $value;

        if (self::is($value)) {
            $referenceParts = explode(self::PART_DELIMITER, $value);
            $this->property = $referenceParts[$this->getPropertyIndex()];
            $this->isValid = true;
        }
    }

    abstract protected static function getObjectName(): string;

    /**
     * @return string[]
     */
    abstract protected static function getProperties(): array;

    abstract protected function getPropertyIndex(): int;

    public static function is(string $value): bool
    {
        return preg_match(self::createPattern(), $value) > 0;
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

    private static function createPattern(): string
    {
        $propertiesPattern = array_reduce(static::getProperties(), function (?string $result, $item) {
            if (is_string($result)) {
                $result .= '|';
            }

            return $result . preg_quote($item, self::PATTERN_DELIMITER);
        });

        return
            self::PATTERN_DELIMITER .
            '^\$' .
            preg_quote(static::getObjectName(), self::PATTERN_DELIMITER) .
            '\.(' . $propertiesPattern . ')$' .
            self::PATTERN_DELIMITER;
    }
}
