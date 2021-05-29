<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataParameter;

interface DataParameterInterface
{
    public function __toString(): string;

    public function getProperty(): string;

    public function isValid(): bool;
}
