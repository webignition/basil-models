<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

class Imports implements ImportsInterface
{
    /**
     * @var array<non-empty-string, string>
     */
    private array $stepPaths;

    /**
     * @var array<non-empty-string, string>
     */
    private array $pagePaths;

    /**
     * @var string[]
     */
    private array $dataProviderPaths;

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
     */
    public function withStepPaths(array $paths): Imports
    {
        $new = clone $this;
        $new->stepPaths = $this->filterPaths($paths);

        return $new;
    }

    /**
     * @param array<mixed> $paths
     */
    public function withPagePaths(array $paths): Imports
    {
        $new = clone $this;
        $new->pagePaths = $this->filterPaths($paths);

        return $new;
    }

    /**
     * @param array<mixed> $paths
     */
    public function withDataProviderPaths(array $paths): Imports
    {
        $new = clone $this;
        $new->dataProviderPaths = $this->filterPaths($paths);

        return $new;
    }

    /**
     * @param array<mixed> $paths
     *
     * @return array<non-empty-string, string>
     */
    private function filterPaths(array $paths): array
    {
        $filteredPaths = [];
        foreach ($paths as $name => $path) {
            $name = is_string($name) ? trim($name) : '';
            $path = is_string($path) ? trim($path) : '';

            if ('' !== $name && '' !== $path) {
                $filteredPaths[$name] = $path;
            }
        }

        return $filteredPaths;
    }
}
