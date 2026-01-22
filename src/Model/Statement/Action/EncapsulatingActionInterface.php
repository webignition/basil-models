<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Action;

use webignition\BasilModels\Model\Statement\EncapsulatingStatementInterface;

interface EncapsulatingActionInterface extends ActionInterface, EncapsulatingStatementInterface
{
    public function getSourceStatement(): ActionInterface;
}
