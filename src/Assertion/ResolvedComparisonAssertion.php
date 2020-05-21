<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class ResolvedComparisonAssertion extends ComparisonAssertion implements ResolvedComparisonAssertionInterface
{
    public const KEY_ENCAPSULATION_SOURCE = 'source';
    public const KEY_ENCAPSULATION_IDENTIFIER = 'identifier';
    public const KEY_ENCAPSULATION_VALUE = 'value';

    private ComparisonAssertionInterface $sourceAssertion;
    private EncapsulatingAssertionData $encapsulatingAssertionData;

    public function __construct(
        ComparisonAssertionInterface $sourceAssertion,
        string $source,
        string $identifier,
        string $value
    ) {
        parent::__construct($source, $identifier, $sourceAssertion->getComparison(), $value);

        $this->sourceAssertion = $sourceAssertion;
        $this->encapsulatingAssertionData = new EncapsulatingAssertionData(
            $sourceAssertion,
            'resolved-comparison-assertion',
            [
                self::KEY_ENCAPSULATION_SOURCE => $this->getSource(),
                self::KEY_ENCAPSULATION_IDENTIFIER => $this->getIdentifier(),
                self::KEY_ENCAPSULATION_VALUE => $this->getValue(),
            ]
        );
    }

    public function getSourceAssertion(): ComparisonAssertionInterface
    {
        return $this->sourceAssertion;
    }

    public function normalise(): AssertionInterface
    {
        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->encapsulatingAssertionData->jsonSerialize();
    }
}
