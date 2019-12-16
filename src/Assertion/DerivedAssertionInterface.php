<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

interface DerivedAssertionInterface extends AssertionInterface
{
    public function getSourceStatement(): StatementInterface;
}
