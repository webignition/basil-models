<?php

declare(strict_types=1);

namespace webignition\BasilModels\Page;

interface PageInterface
{
    public function getUrl(): string;
    public function getIdentifier(string $name): ?string;

    /**
     * @return string[]
     */
    public function getIdentifiers(): array;
}
