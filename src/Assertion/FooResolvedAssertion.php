<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\EncapsulatingStatementData;

class FooResolvedAssertion implements FooResolvedAssertionInterface
{
    private FooAssertionInterface $sourceAssertion;
    private FooAssertionInterface $assertion;
    private EncapsulatingStatementData $encapsulatingStatementData;

    public function __construct(
        FooAssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value = null
    ) {
        $this->sourceAssertion = $sourceAssertion;
        $this->assertion = $this->createAssertion($sourceAssertion, $identifier, $value);
        $this->encapsulatingStatementData = $this->createEncapsulatingStatementData(
            $sourceAssertion,
            $identifier,
            $value
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

    public function equals(FooAssertionInterface $assertion): bool
    {
        return $this->assertion->equals($assertion);
    }

    public function normalise(): FooAssertionInterface
    {
        return $this;
    }

    public function getSourceAssertion(): FooAssertionInterface
    {
        return $this->sourceAssertion;
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

    private function createAssertion(
        FooAssertionInterface $sourceAssertion,
        ?string $identifier,
        ?string $value
    ): FooAssertionInterface {
        $operator = $sourceAssertion->getOperator();

        $source = $identifier . ' ' . $operator;
        if (null !== $value) {
            $source .= ' ' . $value;
        }

        return new FooAssertion($source, $identifier, $operator, $value);
    }

    private function createEncapsulatingStatementData(
        FooAssertionInterface $sourceAssertion,
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
