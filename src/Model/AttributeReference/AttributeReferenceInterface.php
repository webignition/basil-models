<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\AttributeReference;

interface AttributeReferenceInterface
{
    public function __toString(): string;

    public function getElementName(): string;

    public function getAttributeName(): string;

    public function isValid(): bool;
}
