<?php

declare(strict_types=1);

namespace webignition\BasilModels\PageElementReference;

interface PageElementReferenceInterface
{
    public function getImportName(): string;
    public function getElementName(): string;
    public function getAttributeName(): string;
    public function isValid(): bool;
    public function __toString(): string;
}
