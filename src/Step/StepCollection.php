<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

class StepCollection implements StepCollectionInterface
{
    private int $iteratorPosition = 0;

    /**
     * @var string[]
     */
    private array $iteratorIndex = [];

    /**
     * @var StepInterface[]
     */
    private array $steps;

    /**
     * @param array<mixed> $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = array_filter($steps, fn ($step) => $step instanceof StepInterface);
        $this->iteratorIndex = array_keys($this->steps);
    }

    public function getStepNames(): array
    {
        $names = $this->iteratorIndex;
        sort($names);

        return $names;
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
}
