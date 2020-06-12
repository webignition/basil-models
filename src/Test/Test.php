<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

use webignition\BasilModels\Step\StepCollectionInterface;
use webignition\BasilModels\Step\StepInterface;

class Test implements TestInterface
{
    private ?string $path = null;
    private ConfigurationInterface $configuration;
    private StepCollectionInterface $steps;

    /**
     * @param ConfigurationInterface $configuration
     * @param StepCollectionInterface $steps
     */
    public function __construct(
        ConfigurationInterface $configuration,
        StepCollectionInterface $steps
    ) {
        $this->configuration = $configuration;
        $this->steps = $steps;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function getSteps(): StepCollectionInterface
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
