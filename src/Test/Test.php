<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

use webignition\BasilModels\Step\StepCollectionInterface;

class Test implements TestInterface
{
    private ?string $path = null;

    public function __construct(
        private ConfigurationInterface $configuration,
        private StepCollectionInterface $steps
    ) {
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
