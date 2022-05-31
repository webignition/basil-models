<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

/**
 * @extends \Iterator<string, StepInterface>
 */
interface StepCollectionInterface extends \Countable, \Iterator
{
    /**
     * @return non-empty-string[]
     */
    public function getStepNames(): array;

    public function current(): ?StepInterface;

    public function count(): int;

    /**
     * The collection key must be the step name.
     *
     * @return non-empty-string
     */
    public function key(): ?string;
}
