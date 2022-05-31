<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model;

interface EncapsulatingStatementInterface extends StatementInterface
{
    public function getSourceStatement(): StatementInterface;
}
