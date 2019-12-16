<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class DerivedElementExistsAssertion extends Assertion implements DerivedAssertionInterface
{
    private const COMPARISON = 'exists';

    private $sourceAssertion;

    public function __construct(ComparisonAssertionInterface $sourceAssertion, string $identifier)
    {
        parent::__construct($identifier . ' ' . self::COMPARISON, $identifier, self::COMPARISON);

        $this->sourceAssertion = $sourceAssertion;
    }

    public function getSourceAssertion(): ComparisonAssertionInterface
    {
        return $this->sourceAssertion;
    }
}
