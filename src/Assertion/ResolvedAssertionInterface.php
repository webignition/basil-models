<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

interface ResolvedAssertionInterface extends AssertionInterface
{
    public function getSourceAssertion(): AssertionInterface;
}
