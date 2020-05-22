<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

interface FooDerivedAssertionInterface extends FooAssertionInterface
{
    public function getSourceStatement(): StatementInterface;
}
