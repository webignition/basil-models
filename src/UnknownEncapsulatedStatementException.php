<?php

declare(strict_types=1);

namespace webignition\BasilModels;

class UnknownEncapsulatedStatementException extends \Exception
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(
        public array $data
    ) {
        parent::__construct('');
    }
}
