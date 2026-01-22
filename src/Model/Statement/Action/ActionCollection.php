<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Action;

final readonly class ActionCollection implements ActionCollectionInterface
{
    /**
     * @param ActionInterface[] $actions
     */
    public function __construct(
        private array $actions,
    ) {}

    /**
     * @return \Traversable<int, ActionInterface>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->actions);
    }

    public function prepend(ActionCollectionInterface $collection): self
    {
        $actions = [];
        foreach ($collection as $action) {
            $actions[] = $action;
        }

        return new ActionCollection(array_merge($actions, $this->actions));
    }
}
