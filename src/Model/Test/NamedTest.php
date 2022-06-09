<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

use webignition\BasilModels\Model\Step\StepCollectionInterface;

class NamedTest implements NamedTestInterface
{
    /**
     * @param non-empty-string $path
     */
    public function __construct(
        private readonly TestInterface $inner,
        private readonly string $path,
    ) {
    }

    public function getName(): string
    {
        return $this->path;
    }

    public function getBrowser(): string
    {
        return $this->inner->getBrowser();
    }

    public function getUrl(): string
    {
        return $this->inner->getUrl();
    }

    public function getSteps(): StepCollectionInterface
    {
        return $this->inner->getSteps();
    }
}
