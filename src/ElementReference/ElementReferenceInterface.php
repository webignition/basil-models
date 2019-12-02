<?php

declare(strict_types=1);

namespace webignition\BasilModels\ElementReference;

interface ElementReferenceInterface
{
    public function getElementName(): string;
    public function isValid(): bool;
    public function __toString(): string;
}
