<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class Assertion implements AssertionInterface
{
    private const KEY_SOURCE = 'source';
    private const KEY_IDENTIFIER = 'identifier';
    private const KEY_COMPARISON = 'comparison';

    private $source;
    private $identifier;
    private $comparison;

    public function __construct(string $source, string $identifier, string $comparison)
    {
        $this->source = $source;
        $this->identifier = $identifier;
        $this->comparison = $comparison;
    }

    public function getSource(): string
    {
        return $this->source;
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

    public function jsonSerialize(): array
    {
        return [
            self::KEY_SOURCE => $this->source,
            self::KEY_IDENTIFIER => $this->identifier,
            self::KEY_COMPARISON => $this->comparison,
        ];
    }

    public function __toString(): string
    {
        return $this->source;
    }
}
