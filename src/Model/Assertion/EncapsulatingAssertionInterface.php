<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

use webignition\BasilModels\Model\EncapsulatingStatementInterface;

interface EncapsulatingAssertionInterface extends AssertionInterface, EncapsulatingStatementInterface
{
    public function getSourceStatement(): AssertionInterface;
}
