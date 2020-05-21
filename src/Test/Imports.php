<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

class Imports implements ImportsInterface
{
    /**
     * @var string[]
     */
    private array $stepPaths = [];

    /**
     * @var string[]
     */
    private array $pagePaths = [];

    /**
     * @var string[]
     */
    private array $dataProviderPaths = [];

    public function __construct()
    {
        $this->stepPaths = [];
        $this->pagePaths = [];
        $this->dataProviderPaths = [];
    }

    public function getStepPaths(): array
    {
        return $this->stepPaths;
    }

    public function getPagePaths(): array
    {
        return $this->pagePaths;
    }

    public function getDataProviderPaths(): array
    {
        return $this->dataProviderPaths;
    }

    /**
     * @param array<mixed> $paths
     *
     * @return Imports
     */
    public function withStepPaths(array $paths): Imports
    {
        $new = clone $this;
        $new->stepPaths = $this->filterPaths($paths);

        return $new;
    }

    /**
     * @param array<mixed> $paths
     *
     * @return Imports
     */
    public function withPagePaths(array $paths): Imports
    {
        $new = clone $this;
        $new->pagePaths = $this->filterPaths($paths);

        return $new;
    }

    /**
     * @param array<mixed> $paths
     *
     * @return Imports
     */
    public function withDataProviderPaths(array $paths): Imports
    {
        $new = clone $this;
        $new->dataProviderPaths = $this->filterPaths($paths);

        return $new;
    }

    /**
     * @param string[] $paths
     *
     * @return string[]
     */
    private function filterPaths(array $paths): array
    {
        return array_filter($paths, function ($path) {
            return is_string($path) && '' !== trim($path);
        });
    }
}
