<?php

declare(strict_types=1);

namespace webignition\BasilModels\Page;

class Page implements PageInterface
{
    private string $importName;
    private string $url;

    /**
     * @var string[]
     */
    private array $identifiers;

    /**
     * @param string $importName
     * @param string $url
     * @param string[] $identifiers
     */
    public function __construct(string $importName, string $url, array $identifiers = [])
    {
        $this->importName = $importName;
        $this->url = $url;
        $this->identifiers = $identifiers;
    }

    public function getImportName(): string
    {
        return $this->importName;
    }

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
        $new = clone $this;
        $new->identifiers = $identifiers;

        return $new;
    }
}
