<?php

declare(strict_types=1);

namespace webignition\BasilModels;

class ArrayAccessor
{
    /**
     * @param array<mixed> $data
     */
    public static function getStringValue(array $data, string $key): string
    {
        $value = $data[$key] ?? '';

        return is_string($value) ? $value : '';
    }
}
