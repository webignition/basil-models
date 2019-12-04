<?php

declare(strict_types=1);

namespace webignition\BasilModels\BrowserProperty;

interface BrowserPropertyInterface
{
    public function getProperty(): string;
    public function isValid(): bool;
    public function __toString(): string;
}
