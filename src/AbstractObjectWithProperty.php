<?php

declare(strict_types=1);

namespace webignition\BasilModels;

abstract class AbstractObjectWithProperty
{
    protected const PART_DELIMITER = '.';
    private const PATTERN_DELIMITER = '/';

    private $value  = '';
    private $property = '';
    private $isValid = false;

    public function __construct(string $value)
    {
        $value = trim($value);
        $this->value = $value;
        $this->property = '';
        $this->isValid = false;

        if (self::is($value)) {
            $referenceParts = explode(self::PART_DELIMITER, $value, 2);
            $this->property = $referenceParts[1];
            $this->isValid = true;
        }
    }

    abstract protected static function getPatternPrefix(): string;
    abstract protected static function getPropertyPattern(): string;

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

    protected function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    protected function getPropertyPart(int $partIndex): string
    {
        if (false === $this->isValid()) {
            return '';
        }

        $propertyParts = explode(self::PART_DELIMITER, $this->getProperty());

        return $propertyParts[$partIndex];
    }

    protected function getValuePart(int $partIndex): string
    {
        if (false === $this->isValid()) {
            return '';
        }

        $valueParts = explode(self::PART_DELIMITER, $this->getValue());

        return $valueParts[$partIndex];
    }

    private static function createPattern(): string
    {
        return
            self::PATTERN_DELIMITER .
            '^' .
            static::getPatternPrefix() .
            '\.' . static::getPropertyPattern() . '$' .
            self::PATTERN_DELIMITER;
    }
}
