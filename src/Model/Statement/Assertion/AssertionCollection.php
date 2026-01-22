<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Assertion;

use webignition\BasilModels\Model\Statement\StatementCollectionInterface;

final readonly class AssertionCollection implements AssertionCollectionInterface
{
    /**
     * @param AssertionInterface[] $assertions
     */
    public function __construct(
        private array $assertions,
    ) {}

    public function prepend(StatementCollectionInterface $collection): static
    {
        $assertions = [];
        foreach ($collection as $statement) {
            if ($statement instanceof AssertionInterface) {
                $assertions[] = $statement;
            }
        }

        return new AssertionCollection(array_merge($assertions, $this->assertions));
    }

    public function append(StatementCollectionInterface $collection): static
    {
        $assertions = $this->assertions;

        foreach ($collection as $statement) {
            if ($statement instanceof AssertionInterface) {
                $assertions[] = $statement;
            }
        }

        return new AssertionCollection($assertions);
    }

    /**
     * @return \Traversable<int, AssertionInterface>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->assertions);
    }
}
