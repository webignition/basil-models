<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

interface ConfigurationInterface
{
    public const VALIDATION_STATE_VALID = 1;
    public const VALIDATION_STATE_BROWSER_EMPTY = 2;
    public const VALIDATION_STATE_URL_EMPTY = 3;

    /**
     * @return string[]
     */
    public function getBrowsers(): array;
    public function getUrl(): string;
    public function validate(): int;
}
