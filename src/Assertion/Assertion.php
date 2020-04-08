<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\Statement;

class Assertion extends Statement implements AssertionInterface
{
    private const KEY_IDENTIFIER = 'identifier';
    public const KEY_COMPARISON = 'comparison';

    private $identifier;
    private $comparison;

    public function __construct(string $source, string $identifier, string $comparison)
    {
        parent::__construct($source);

        $this->identifier = $identifier;
        $this->comparison = $comparison;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getComparison(): string
    {
        return $this->comparison;
    }

    public function withComparison(string $comparison): AssertionInterface
    {
        $oldComparison = $this->getComparison();

        $new = clone $this;
        $new->comparison = $comparison;
        $new->source = preg_replace(
            '/' . $oldComparison . '$/',
            $comparison,
            $this->source
        );

        return $new;
    }

    public function withIdentifier(string $identifier): AssertionInterface
    {
        $new = clone $this;
        $new->identifier = $identifier;

        return $new;
    }

    public function equals(AssertionInterface $assertion): bool
    {
        if ($this->identifier !== $assertion->getIdentifier()) {
            return false;
        }

        return $this->comparison === $assertion->getComparison();
    }

    public function normalise(): AssertionInterface
    {
        $new = clone $this;
        $new->source = $this->identifier . ' ' . $this->comparison;

        return $new;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_IDENTIFIER => $this->identifier,
            self::KEY_COMPARISON => $this->comparison,
        ]);
    }

    public static function fromArray(array $data): ?AssertionInterface
    {
        $source = $data[self::KEY_SOURCE] ?? null;
        $identifier = $data[self::KEY_IDENTIFIER] ?? null;
        $comparison = $data[self::KEY_COMPARISON] ?? null;

        if (null === $source || null === $identifier || null === $comparison) {
            return null;
        }

        return new Assertion((string) $source, (string) $identifier, (string) $comparison);
    }

    public static function createsFromComparison(string $comparison): bool
    {
        return in_array($comparison, ['exists', 'not-exists']);
    }

    public function __toString(): string
    {
        return $this->source;
    }
}
