<?php

declare(strict_types=1);

namespace webignition\BasilModels\Page;

class Page implements PageInterface
{
    private $url;
    private $identifiers;

    public function __construct(string $url, array $identifiers = [])
    {
        $this->url = $url;
        $this->identifiers = $identifiers;
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
}
