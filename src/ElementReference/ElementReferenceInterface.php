<?php

declare(strict_types=1);

namespace webignition\BasilModels\ElementReference;

interface ElementReferenceInterface
{
    public function __toString(): string;

    public function getElementName(): string;

    public function isValid(): bool;
}
