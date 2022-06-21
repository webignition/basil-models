<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Exception;

interface UnparseableDataExceptionInterface extends \Throwable
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
