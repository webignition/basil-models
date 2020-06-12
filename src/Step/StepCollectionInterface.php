<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

/**
 * @extends \Iterator<StepInterface>
 */
interface StepCollectionInterface extends \Iterator
{
    /**
     * @return string[]
     */
    public function getStepNames(): array;

    public function current(): ?StepInterface;
}
