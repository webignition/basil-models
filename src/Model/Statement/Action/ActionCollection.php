<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Action;

use webignition\BasilModels\Model\Statement\StatementCollectionInterface;

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

    public function prepend(StatementCollectionInterface $collection): static
    {
        $actions = [];
        foreach ($collection as $statement) {
            if ($statement instanceof ActionInterface) {
                $actions[] = $statement;
            }
        }

        return new ActionCollection(array_merge($actions, $this->actions));
    }

    public function append(StatementCollectionInterface $collection): static
    {
        $actions = $this->actions;
        foreach ($collection as $statement) {
            if ($statement instanceof ActionInterface) {
                $actions[] = $statement;
            }
        }

        return new ActionCollection($actions);
    }
}
