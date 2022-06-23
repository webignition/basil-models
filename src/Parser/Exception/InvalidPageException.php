<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Exception;

class InvalidPageException extends \Exception
{
    public const CODE_URL_EMPTY = 100;

    public static function createForEmptyUrl(): self
    {
        return new InvalidPageException(
            'url is empty',
            self::CODE_URL_EMPTY
        );
    }
}
