<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Exception;

interface ParserExceptionInterface extends \Throwable
{
    public function getIntCode(): int;
}
