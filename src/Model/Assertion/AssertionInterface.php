<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

use webignition\BasilModels\Model\StatementInterface;

interface AssertionInterface extends StatementInterface
{
    public function getOperator(): string;

    public function equals(AssertionInterface $assertion): bool;

    public function normalise(): AssertionInterface;

    public function isComparison(): bool;
}
