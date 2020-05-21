<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class ResolvedComparisonAssertion extends ComparisonAssertion implements ResolvedComparisonAssertionInterface
{
    public const KEY_ENCAPSULATION = 'encapsulation';
    public const KEY_ENCAPSULATION_TYPE = 'type';
    public const KEY_ENCAPSULATION_SOURCE_TYPE = 'source_type';
    public const KEY_ENCAPSULATION_SOURCE = 'source';
    public const KEY_ENCAPSULATION_IDENTIFIER = 'identifier';
    public const KEY_ENCAPSULATION_VALUE = 'value';
    public const KEY_ENCAPSULATES = 'encapsulates';

    private ComparisonAssertionInterface $sourceAssertion;

    public function __construct(
        ComparisonAssertionInterface $sourceAssertion,
        string $source,
        string $identifier,
        string $value
    ) {
        parent::__construct($source, $identifier, $sourceAssertion->getComparison(), $value);

        $this->sourceAssertion = $sourceAssertion;
    }

    public function getSourceAssertion(): ComparisonAssertionInterface
    {
        return $this->sourceAssertion;
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_ENCAPSULATION => [
                self::KEY_ENCAPSULATION_TYPE => 'resolved-comparison-assertion',
                self::KEY_ENCAPSULATION_SOURCE_TYPE => 'assertion',
                self::KEY_ENCAPSULATION_SOURCE => $this->getSource(),
                self::KEY_ENCAPSULATION_IDENTIFIER => $this->getIdentifier(),
                self::KEY_ENCAPSULATION_VALUE => $this->getValue(),
            ],
            self::KEY_ENCAPSULATES => $this->sourceAssertion->jsonSerialize(),
        ];
    }
}
