<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\StatementInterface;

interface ActionInterface extends StatementInterface
{
    public function getType(): string;
    public function getArguments(): string;

    /**
     * @param array<mixed> $data
     *
     * @return ActionInterface
     */
    public static function fromArray(array $data): ActionInterface;
}
