<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class ResolvedAssertion extends Assertion implements ResolvedAssertionInterface
{
    public const KEY_ENCAPSULATION = 'encapsulation';
    public const KEY_ENCAPSULATION_TYPE = 'type';
    public const KEY_ENCAPSULATION_SOURCE_TYPE = 'source_type';
    public const KEY_ENCAPSULATION_SOURCE = 'source';
    public const KEY_ENCAPSULATION_IDENTIFIER = 'identifier';
    public const KEY_ENCAPSULATES = 'encapsulates';

    private AssertionInterface $sourceAssertion;

    public function __construct(
        AssertionInterface $sourceAssertion,
        string $source,
        string $identifier
    ) {
        parent::__construct($source, $identifier, $sourceAssertion->getComparison());

        $this->sourceAssertion = $sourceAssertion;
    }

    public function getSourceAssertion(): AssertionInterface
    {
        return $this->sourceAssertion;
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_ENCAPSULATION => [
                self::KEY_ENCAPSULATION_TYPE => 'resolved-assertion',
                self::KEY_ENCAPSULATION_SOURCE_TYPE => 'assertion',
                self::KEY_ENCAPSULATION_SOURCE => $this->getSource(),
                self::KEY_ENCAPSULATION_IDENTIFIER => $this->getIdentifier(),
            ],
            self::KEY_ENCAPSULATES => $this->sourceAssertion->jsonSerialize(),
        ];
    }
}
