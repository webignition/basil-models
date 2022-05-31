<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Step;

use webignition\BasilModels\Model\Step\StepInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;

class StepProvider implements StepProviderInterface
{
    /**
     * @var StepInterface[]
     */
    private array $items = [];

    /**
     * @param array<mixed> $steps
     */
    public function __construct(array $steps)
    {
        foreach ($steps as $name => $step) {
            if ($step instanceof StepInterface) {
                $this->items[$name] = $step;
            }
        }
    }

    /**
     * @throws UnknownItemException
     */
    public function find(string $name): StepInterface
    {
        $step = $this->items[$name] ?? null;

        if (null === $step) {
            throw new UnknownItemException(UnknownItemException::TYPE_STEP, $name);
        }

        return $step;
    }
}
