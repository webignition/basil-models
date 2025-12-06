<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model;

abstract class AbstractObjectWithProperty implements \Stringable
{
    protected const PART_DELIMITER = '.';
    private const PATTERN_DELIMITER = '/';

    private string $value;
    private string $property;
    private bool $isValid;

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

    public function __toString(): string
    {
        return $this->value;
    }

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

    abstract protected static function getPatternPrefix(): string;

    abstract protected static function getPropertyPattern(): string;

    protected function getValue(): string
    {
        return $this->value;
    }

    protected function getMaxPartCount(): int
    {
        return 2;
    }

    protected function getPropertyPart(int $partIndex): string
    {
        if (false === $this->isValid()) {
            return '';
        }

        $propertyParts = explode(self::PART_DELIMITER, $this->getProperty(), $this->getMaxPartCount());

        return $propertyParts[$partIndex] ?? '';
    }

    protected function getValuePart(int $partIndex): string
    {
        if (false === $this->isValid()) {
            return '';
        }

        $valueParts = explode(self::PART_DELIMITER, $this->getValue(), $this->getMaxPartCount());

        return $valueParts[$partIndex] ?? '';
    }

    private static function createPattern(): string
    {
        return
            self::PATTERN_DELIMITER
            . '^'
            . static::getPatternPrefix()
            . '\.' . static::getPropertyPattern() . '$'
            . self::PATTERN_DELIMITER;
    }
}
