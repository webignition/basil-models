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
     * @return array<string, string>
     */
    public function getIdentifiers(): array;

    /**
     * @param array<string, string> $identifiers
     */
    public function withIdentifiers(array $identifiers): PageInterface;
}
