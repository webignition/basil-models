<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

use webignition\BasilModels\Step\StepInterface;

class Test implements TestInterface
{
    /**
     * @var string|null
     */
    private $path = null;
    private $configuration;
    private $steps = [];

    /**
     * @param ConfigurationInterface $configuration
     * @param StepInterface[] $steps
     */
    public function __construct(
        ConfigurationInterface $configuration,
        array $steps
    ) {
        $this->configuration = $configuration;
        $this->steps = [];

        foreach ($steps as $stepName => $step) {
            if ($step instanceof StepInterface) {
                $this->steps[(string) $stepName] = $step;
            }
        }
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @return StepInterface[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    public function withPath(string $path): TestInterface
    {
        $new = clone $this;
        $new->path = $path;

        return $new;
    }
}
