<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\StatementInterface;

interface FooResolvedActionInterface extends FooActionInterface
{
    public function getSourceAction(): FooActionInterface;
}
