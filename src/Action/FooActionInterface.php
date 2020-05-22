<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\StatementInterface;

interface FooActionInterface extends StatementInterface
{
    public function getType(): string;
    public function getArguments(): ?string;
    public function getIdentifier(): ?string;
    public function getValue(): ?string;
}
