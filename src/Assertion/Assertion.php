<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class Assertion implements AssertionInterface
{
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

    public function withIdentifier(string $identifier): AssertionInterface
    {
        $new = clone $this;
        $new->identifier = $identifier;

        return $new;
    }
}
