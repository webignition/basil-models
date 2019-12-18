<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

use webignition\BasilModels\Step\StepInterface;

class Test implements TestInterface
{
    private $path = '';
    private $configuration;
    private $imports;
    private $steps = [];

    public function __construct(
        string $path,
        ConfigurationInterface $configuration,
        array $steps,
        ?ImportsInterface $imports = null
    ) {
        $this->path = $path;
        $this->configuration = $configuration;
        $this->imports = $imports ?? new Imports();
        $this->steps = [];

        foreach ($steps as $stepName => $step) {
            if ($step instanceof StepInterface) {
                $this->steps[(string) $stepName] = $step;
            }
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function getImports(): ImportsInterface
    {
        return $this->imports;
    }

    /**
     * @return StepInterface[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }
}
