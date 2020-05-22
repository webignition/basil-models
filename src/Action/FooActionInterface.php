<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\FooStatementInterface;

interface FooActionInterface extends FooStatementInterface
{
    public function getType(): string;
    public function getArguments(): ?string;
    public function getIdentifier(): ?string;
    public function getValue(): ?string;
}
