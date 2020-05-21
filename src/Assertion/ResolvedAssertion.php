<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class ResolvedAssertion extends Assertion implements ResolvedAssertionInterface
{
    public const KEY_ENCAPSULATION_SOURCE = 'source';
    public const KEY_ENCAPSULATION_IDENTIFIER = 'identifier';

    private AssertionInterface $sourceAssertion;
    private EncapsulatingAssertionData $encapsulatingAssertionData;

    public function __construct(
        AssertionInterface $sourceAssertion,
        string $source,
        string $identifier
    ) {
        parent::__construct($source, $identifier, $sourceAssertion->getComparison());

        $this->sourceAssertion = $sourceAssertion;
        $this->encapsulatingAssertionData = new EncapsulatingAssertionData($sourceAssertion, 'resolved-assertion', [
            self::KEY_ENCAPSULATION_SOURCE => $source,
            self::KEY_ENCAPSULATION_IDENTIFIER => $identifier,
        ]);
    }

    public function normalise(): AssertionInterface
    {
        return $this;
    }

    public function getSourceAssertion(): AssertionInterface
    {
        return $this->sourceAssertion;
    }

    public function jsonSerialize(): array
    {
        return $this->encapsulatingAssertionData->jsonSerialize();
    }
}
