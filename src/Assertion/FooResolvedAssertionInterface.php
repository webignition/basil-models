<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

interface FooResolvedAssertionInterface extends FooAssertionInterface
{
    public function getSourceAssertion(): FooAssertionInterface;
}
