<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\FooStatementInterface;

interface FooAssertionInterface extends FooStatementInterface
{
    public function getIdentifier(): string;
    public function getOperator(): string;
    public function getValue(): ?string;
    public function equals(FooAssertionInterface $assertion): bool;
    public function normalise(): FooAssertionInterface;
}
