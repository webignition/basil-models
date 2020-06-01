<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\EncapsulatingStatementInterface;

interface EncapsulatingAssertionInterface extends AssertionInterface, EncapsulatingStatementInterface
{
    public function getSourceStatement(): AssertionInterface;
}
