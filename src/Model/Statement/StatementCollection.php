<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement;

final readonly class StatementCollection implements StatementCollectionInterface
{
    /**
     * @param StatementInterface[] $statements
     */
    public function __construct(
        private array $statements,
    ) {}

    /**
     * @return \Traversable<int, StatementInterface>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->statements);
    }

    public function prepend(StatementCollectionInterface $collection): static
    {
        $statements = [];
        foreach ($collection as $statement) {
            $statements[] = $statement;
        }

        return new StatementCollection(array_merge($statements, $this->statements));
    }

    public function append(StatementCollectionInterface $collection): static
    {
        $actions = $this->statements;
        foreach ($collection as $statement) {
            $actions[] = $statement;
        }

        return new StatementCollection($actions);
    }
}
