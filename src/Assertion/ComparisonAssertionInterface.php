<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

interface ComparisonAssertionInterface extends AssertionInterface
{
    public function getValue(): string;
}
