<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Step;

class StepCollection implements StepCollectionInterface
{
    private int $iteratorPosition = 0;

    /**
     * @var non-empty-string[]
     */
    private array $iteratorIndex;

    /**
     * @var array<non-empty-string, StepInterface>
     */
    private array $steps;

    /**
     * @param array<mixed> $steps
     */
    public function __construct(array $steps)
    {
        $filteredSteps = [];
        foreach ($steps as $stepName => $step) {
            if (is_string($stepName) && '' !== $stepName && $step instanceof StepInterface) {
                $filteredSteps[$stepName] = $step;
            }
        }

        $this->steps = $filteredSteps;
        $this->iteratorIndex = array_keys($this->steps);
    }

    public function getStepNames(): array
    {
        return $this->iteratorIndex;
    }

    public function current(): ?StepInterface
    {
        return $this->steps[$this->key()] ?? null;
    }

    public function next(): void
    {
        ++$this->iteratorPosition;
    }

    public function key(): ?string
    {
        return $this->iteratorIndex[$this->iteratorPosition] ?? null;
    }

    public function valid(): bool
    {
        return null !== $this->key();
    }

    public function rewind(): void
    {
        $this->iteratorPosition = 0;
    }

    public function count(): int
    {
        return count($this->steps);
    }
}
