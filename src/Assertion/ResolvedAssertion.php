<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class ResolvedAssertion implements ResolvedAssertionInterface
{
    private AssertionInterface $sourceAssertion;
    private AssertionInterface $resolvedAssertion;
    private EncapsulatingAssertionData $encapsulatingAssertionData;

    public function __construct(AssertionInterface $sourceAssertion, string $source, string $identifier)
    {
        $this->sourceAssertion = $sourceAssertion;
        $this->resolvedAssertion = new Assertion($source, $identifier, $sourceAssertion->getComparison());

        $this->encapsulatingAssertionData = new EncapsulatingAssertionData($sourceAssertion, 'resolved-assertion', [
            'source' => $source,
            'identifier' => $identifier,
        ]);
    }

    public function getIdentifier(): string
    {
        return $this->resolvedAssertion->getIdentifier();
    }

    public function getComparison(): string
    {
        return $this->resolvedAssertion->getComparison();
    }

    public function equals(AssertionInterface $assertion): bool
    {
        return $this->resolvedAssertion->equals($assertion);
    }

    public function normalise(): AssertionInterface
    {
        return $this;
    }

    public function getSourceAssertion(): AssertionInterface
    {
        return $this->sourceAssertion;
    }

    public function getSource(): string
    {
        return $this->resolvedAssertion->getSource();
    }

    public function __toString(): string
    {
        return (string) $this->sourceAssertion;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->encapsulatingAssertionData->jsonSerialize();
    }
}
