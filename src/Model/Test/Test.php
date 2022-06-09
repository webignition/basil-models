<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

use webignition\BasilModels\Model\Step\StepCollectionInterface;

class Test implements TestInterface
{
    public function __construct(
        private readonly string $browser,
        private readonly string $url,
        private readonly StepCollectionInterface $steps,
    ) {
    }

    public function getBrowser(): string
    {
        return $this->browser;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSteps(): StepCollectionInterface
    {
        return $this->steps;
    }
}
