<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\PageUrlReference;

interface PageUrlReferenceInterface
{
    public function __toString(): string;

    public function getImportName(): string;

    public function isValid(): bool;
}
