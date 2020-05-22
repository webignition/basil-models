<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

interface AssertionInterface extends StatementInterface
{
    public function getIdentifier(): string;
    public function getOperator(): string;
    public function getValue(): ?string;
    public function equals(AssertionInterface $assertion): bool;
    public function normalise(): AssertionInterface;
}
