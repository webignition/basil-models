<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

use webignition\BasilModels\Model\StatementInterface;

interface ActionInterface extends StatementInterface
{
    public function getType(): string;

    public function getArguments(): ?string;

    public function isBrowserOperation(): bool;

    public function isInteraction(): bool;

    public function isInput(): bool;

    public function isWait(): bool;
}
