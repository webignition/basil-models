<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Assertion;

use webignition\BasilModels\Model\Statement\EncapsulatingStatementInterface;

interface EncapsulatingAssertionInterface extends AssertionInterface, EncapsulatingStatementInterface
{
    public function getSourceStatement(): AssertionInterface;
}
