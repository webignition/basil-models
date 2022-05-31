<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

use webignition\BasilModels\Model\EncapsulatingStatementInterface;

interface EncapsulatingActionInterface extends ActionInterface, EncapsulatingStatementInterface
{
    public function getSourceStatement(): ActionInterface;
}
