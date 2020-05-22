<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class FooDerivedValueOperationAssertion implements FooDerivedAssertionInterface
{
    private StatementInterface $sourceStatement;
    private FooAssertionInterface $assertion;

    public function __construct(StatementInterface $sourceStatement, string $value, string $operator)
    {
        $this->sourceStatement = $sourceStatement;
        $this->assertion = new FooAssertion($value . ' ' . $operator, $value, $operator);
    }

    public function getIdentifier(): string
    {
        return $this->assertion->getIdentifier();
    }

    public function getOperator(): string
    {
        return $this->assertion->getOperator();
    }

    public function getValue(): ?string
    {
        return $this->assertion->getValue();
    }

    public function equals(FooAssertionInterface $assertion): bool
    {
        return $this->assertion->equals($assertion);
    }

    public function normalise(): FooAssertionInterface
    {
        return $this;
    }

    public function getSourceStatement(): StatementInterface
    {
        return $this->sourceStatement;
    }

    public function getSource(): string
    {
        return $this->assertion->getSource();
    }

    public function __toString(): string
    {
        return (string) $this->assertion;
    }

    public function jsonSerialize(): array
    {
        return [
            'encapsulation' => [
                'container' => 'derived-value-operation-assertion',
                'value' => $this->assertion->getIdentifier(),
                'operator' => $this->assertion->getOperator(),
            ],
            'encapsulates' => $this->sourceStatement->jsonSerialize(),
        ];
    }
}
