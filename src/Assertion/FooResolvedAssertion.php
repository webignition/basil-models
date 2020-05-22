<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class FooResolvedAssertion implements FooResolvedAssertionInterface
{
    private FooAssertionInterface $sourceAssertion;
    private FooAssertionInterface $assertion;

    public function __construct(
        FooAssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value = null
    ) {
        $this->sourceAssertion = $sourceAssertion;

        $source = $identifier . ' ' . $sourceAssertion->getOperator();
        if (null !== $value) {
            $source .= ' ' . $value;
        }

        $this->assertion = new FooAssertion($source, $identifier, $sourceAssertion->getOperator(), $value);
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
        $encapsulationData = [
            'container' => 'resolved-assertion',
            'source' => $this->assertion->getSource(),
            'identifier' => $this->assertion->getIdentifier(),
        ];

        $value = $this->assertion->getValue();
        if (null !== $value) {
            $encapsulationData['value'] = $value;
        }

        return [
            'encapsulation' => $encapsulationData,
            'encapsulates' => $this->sourceAssertion->jsonSerialize(),
        ];
    }
}
