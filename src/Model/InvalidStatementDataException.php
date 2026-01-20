<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model;

class InvalidStatementDataException extends \Exception
{
    public function __construct(
        public readonly string $statementJson,
    ) {
        parent::__construct();
    }
}
