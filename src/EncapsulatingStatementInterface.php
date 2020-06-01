<?php

declare(strict_types=1);

namespace webignition\BasilModels;

interface EncapsulatingStatementInterface extends StatementInterface
{
    public function getSourceStatement(): StatementInterface;
}
