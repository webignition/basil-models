<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface FooResolvedActionInterface extends FooActionInterface
{
    public function getSourceAction(): FooActionInterface;
}
