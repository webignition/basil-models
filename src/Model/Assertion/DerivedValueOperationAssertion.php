<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\EncapsulatingStatementData;
use webignition\BasilModels\Model\EncapsulatingStatementInterface;
use webignition\BasilModels\Model\StatementInterface;

class DerivedValueOperationAssertion implements AssertionInterface, EncapsulatingStatementInterface, \Stringable
{
    private AssertionInterface $assertion;
    private EncapsulatingStatementData $encapsulatingStatementData;

    public function __construct(
        private readonly StatementInterface $sourceStatement,
        string $value,
        string $operator
    ) {
        $this->assertion = new Assertion($value . ' ' . $operator, $value, $operator);
        $this->encapsulatingStatementData = $this->createEncapsulatingStatementData(
            $sourceStatement,
            $value,
            $operator
        );
    }

    public function __toString(): string
    {
        return (string) $this->assertion;
    }

    public function getStatementType(): StatementType
    {
        return StatementType::ASSERTION;
    }

    public function getIdentifier(): ?string
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

    public function isComparison(): bool
    {
        return $this->assertion->isComparison();
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
