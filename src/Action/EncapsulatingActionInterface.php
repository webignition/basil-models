<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\EncapsulatingStatementInterface;

interface EncapsulatingActionInterface extends ActionInterface, EncapsulatingStatementInterface
{
    public function getSourceStatement(): ActionInterface;
}
