<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

use webignition\BasilModels\Model\Step\StepCollectionInterface;

interface TestInterface
{
    /**
     * @return non-empty-string
     */
    public function getBrowser(): string;

    /**
     * @return non-empty-string
     */
    public function getUrl(): string;

    public function getSteps(): StepCollectionInterface;
}
