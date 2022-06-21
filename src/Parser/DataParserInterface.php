<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser;

use webignition\BasilModels\Parser\Exception\UnparseableDataExceptionInterface;

interface DataParserInterface
{
    /**
     * @param array<mixed> $data
     *
     * @throws UnparseableDataExceptionInterface
     */
    public function parse(array $data): mixed;
}
