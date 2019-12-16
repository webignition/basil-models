<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

interface DerivedAssertionInterface extends AssertionInterface
{
    public function getSourceAssertion(): ComparisonAssertionInterface;
}
