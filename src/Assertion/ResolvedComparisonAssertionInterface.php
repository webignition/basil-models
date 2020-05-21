<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

interface ResolvedComparisonAssertionInterface extends ComparisonAssertionInterface
{
    public function getSourceAssertion(): ComparisonAssertionInterface;
}
