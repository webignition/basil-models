<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Exception;

class UnparseableActionException extends UnparseableStatementException
{
    public const CODE_EMPTY = 1;
    public const CODE_EMPTY_INPUT_ACTION_VALUE = 2;
    public const CODE_INVALID_IDENTIFIER = 3;

    protected function __construct(string $actionString, int $code)
    {
        parent::__construct($actionString, sprintf('Unparseable action "%s"', $actionString), $code);
    }

    public static function createEmptyActionException(): UnparseableActionException
    {
        return new UnparseableActionException('', self::CODE_EMPTY);
    }

    public static function createEmptyInputActionValueException(string $actionString): UnparseableActionException
    {
        return new UnparseableActionException($actionString, self::CODE_EMPTY_INPUT_ACTION_VALUE);
    }

    public static function createInvalidIdentifierException(string $actionString): UnparseableActionException
    {
        return new UnparseableActionException($actionString, self::CODE_INVALID_IDENTIFIER);
    }

    public function isEmptyActionException(): bool
    {
        return self::CODE_EMPTY === $this->code;
    }

    public function isEmptyInputActionValueException(): bool
    {
        return self::CODE_EMPTY_INPUT_ACTION_VALUE === $this->code;
    }

    public function isInvalidIdentifierException(): bool
    {
        return self::CODE_INVALID_IDENTIFIER === $this->code;
    }
}
