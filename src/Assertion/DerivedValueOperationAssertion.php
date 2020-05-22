<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\EncapsulatingStatementData;
use webignition\BasilModels\StatementInterface;

class DerivedValueOperationAssertion implements DerivedAssertionInterface
{
    private StatementInterface $sourceStatement;
    private AssertionInterface $assertion;
    private EncapsulatingStatementData $encapsulatingStatementData;

    public function __construct(StatementInterface $sourceStatement, string $value, string $operator)
    {
        $this->sourceStatement = $sourceStatement;
        $this->assertion = new Assertion($value . ' ' . $operator, $value, $operator);
        $this->encapsulatingStatementData = $this->createEncapsulatingStatementData(
            $sourceStatement,
            $value,
            $operator
        );
    }

    public function getStatementType(): string
    {
        return 'assertion';
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

    public function equals(AssertionInterface $assertion): bool
    {
        return $this->assertion->equals($assertion);
    }

    public function normalise(): AssertionInterface
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
        return $this->encapsulatingStatementData->jsonSerialize();
    }

    private function createEncapsulatingStatementData(
        StatementInterface $sourceStatement,
        string $value,
        string $operator
    ): EncapsulatingStatementData {
        return new EncapsulatingStatementData(
            $sourceStatement,
            'derived-value-operation-assertion',
            [
                'value' => $value,
                'operator' => $operator,
            ]
        );
    }
}