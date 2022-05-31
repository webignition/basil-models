<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

use webignition\BasilModels\Model\EncapsulatingStatementData;

class ResolvedAssertion implements AssertionInterface, EncapsulatingAssertionInterface, \Stringable
{
    private AssertionInterface $assertion;
    private EncapsulatingStatementData $encapsulatingStatementData;

    public function __construct(
        private readonly AssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value = null
    ) {
        $this->assertion = $this->createAssertion($sourceAssertion, $identifier, $value);
        $this->encapsulatingStatementData = $this->createEncapsulatingStatementData(
            $sourceAssertion,
            $identifier,
            $value
        );
    }

    public function __toString(): string
    {
        return (string) $this->assertion;
    }

    public function getStatementType(): string
    {
        return 'assertion';
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

    public function getSourceStatement(): AssertionInterface
    {
        return $this->sourceAssertion;
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

    private function createAssertion(
        AssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value
    ): AssertionInterface {
        $operator = $sourceAssertion->getOperator();

        $source = $identifier . ' ' . $operator;
        if (null !== $value) {
            $source .= ' ' . $value;
        }

        return new Assertion($source, $identifier, $operator, $value);
    }

    private function createEncapsulatingStatementData(
        AssertionInterface $sourceAssertion,
        ?string $identifier,
        ?string $value
    ): EncapsulatingStatementData {
        $encapsulatingStatementData = [
            'identifier' => $identifier,
        ];

        if (null !== $value) {
            $encapsulatingStatementData['value'] = $value;
        }

        return new EncapsulatingStatementData(
            $sourceAssertion,
            'resolved-assertion',
            $encapsulatingStatementData
        );
    }
}
