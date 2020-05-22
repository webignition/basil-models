<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface ResolvedActionInterface extends ActionInterface
{
    public function getSourceAction(): ActionInterface;
}
