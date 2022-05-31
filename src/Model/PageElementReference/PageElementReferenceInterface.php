<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\PageElementReference;

interface PageElementReferenceInterface
{
    public function __toString(): string;

    public function getImportName(): string;

    public function getElementName(): string;

    public function getAttributeName(): string;

    public function isValid(): bool;
}
