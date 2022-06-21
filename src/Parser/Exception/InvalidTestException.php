<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Exception;

class InvalidTestException extends \Exception
{
    public const CODE_BROWSER_EMPTY = 100;
    public const CODE_URL_EMPTY = 200;

    public static function createForEmptyBrowser(): self
    {
        return new InvalidTestException(
            'config.browser is empty',
            self::CODE_BROWSER_EMPTY
        );
    }

    public static function createForEmptyUrl(): self
    {
        return new InvalidTestException(
            'config.url is empty',
            self::CODE_URL_EMPTY
        );
    }
}
