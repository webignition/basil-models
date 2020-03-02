<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\StatementInterface;

interface ActionInterface extends StatementInterface, \JsonSerializable
{
    public function getType(): string;
    public function getArguments(): string;

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
