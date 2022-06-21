<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Exception;

abstract class AbstractParserException extends \Exception implements ParserExceptionInterface
{
    public function getIntCode(): int
    {
        $code = parent::getCode();

        return is_int($code) ? $code : 0;
    }
}
