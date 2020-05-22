<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\FooStatementInterface;

interface FooDerivedAssertionInterface extends FooAssertionInterface
{
    public function getSourceStatement(): FooStatementInterface;
}
