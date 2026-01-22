<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Assertion;

use webignition\BasilModels\Model\Statement\StatementCollectionInterface;

final readonly class UniqueAssertionCollection implements AssertionCollectionInterface
{
    /**
     * @var AssertionInterface[]
     */
    private array $assertions;

    /**
     * @param AssertionInterface[] $assertions
     */
    public function __construct(array $assertions = [])
    {
        $uniqueAssertions = [];
        foreach ($assertions as $assertion) {
            if (!$this->contains($uniqueAssertions, $assertion)) {
                $uniqueAssertions[] = $assertion;
            }
        }

        $this->assertions = $uniqueAssertions;
    }

    public function prepend(StatementCollectionInterface $collection): static
    {
        $assertions = [];
        foreach ($collection as $statement) {
            if ($statement instanceof AssertionInterface) {
                $assertions[] = $statement;
            }
        }

        $assertions = array_merge($assertions, $this->assertions);
        $new = new UniqueAssertionCollection($assertions);

        return $new->normalise();
    }

    public function append(StatementCollectionInterface $collection): static
    {
        $new = new UniqueAssertionCollection($this->assertions);
        foreach ($collection as $statement) {
            if ($statement instanceof AssertionInterface) {
                $new = $new->add($statement);
            }
        }

        return $new->normalise();
    }

    /**
     * @return \Traversable<int, AssertionInterface>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->assertions);
    }

    public function normalise(): UniqueAssertionCollection
    {
        $normalisedCollection = new UniqueAssertionCollection();

        foreach ($this as $assertion) {
            $normalisedCollection = $normalisedCollection->add($assertion->normalise());
        }

        return $normalisedCollection;
    }

    private function add(AssertionInterface $assertion): self
    {
        if ($this->contains($this->assertions, $assertion)) {
            return $this;
        }

        $assertions = $this->assertions;
        $assertions[] = $assertion;

        return new UniqueAssertionCollection($assertions);
    }

    /**
     * @param AssertionInterface[] $assertions
     */
    private function contains(array $assertions, AssertionInterface $assertion): bool
    {
        foreach ($assertions as $comparator) {
            if ($assertion->equals($comparator)) {
                return true;
            }
        }

        return false;
    }
}
