<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Page;

interface PageInterface
{
    public function getImportName(): string;

    /**
     * @return non-empty-string
     */
    public function getUrl(): string;

    public function getIdentifier(string $name): ?string;

    /**
     * @return string[]
     */
    public function getIdentifiers(): array;

    /**
     * @param string[] $identifiers
     */
    public function withIdentifiers(array $identifiers): PageInterface;
}
