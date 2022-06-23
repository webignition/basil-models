<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Page;

class Page implements PageInterface
{
    /**
     * @param non-empty-string $url
     * @param string[]         $identifiers
     */
    public function __construct(
        private readonly string $importName,
        private readonly string $url,
        private readonly array $identifiers = []
    ) {
    }

    public function getImportName(): string
    {
        return $this->importName;
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    public function getIdentifier(string $name): ?string
    {
        return $this->identifiers[$name] ?? null;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    public function withIdentifiers(array $identifiers): PageInterface
    {
        return new Page($this->importName, $this->url, $identifiers);
    }
}
