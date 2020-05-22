<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

/**
 * @implements \IteratorAggregate<FooAssertionInterface>
 */
class UniqueAssertionCollection implements \IteratorAggregate
{
    /**
     * @var FooAssertionInterface[]
     */
    private array $assertions = [];

    /**
     * @param FooAssertionInterface[] $assertions
     */
    public function __construct(array $assertions = [])
    {
        foreach ($assertions as $assertion) {
            if ($assertion instanceof FooAssertionInterface) {
                $this->add($assertion);
            }
        }
    }

    public function add(FooAssertionInterface $assertion): void
    {
        if (!$this->contains($assertion)) {
            $this->assertions[] = $assertion;
        }
    }

    public function merge(UniqueAssertionCollection $collection): UniqueAssertionCollection
    {
        $new = clone $this;

        foreach ($collection as $assertion) {
            $new->add($assertion);
        }

        return $new->normalise();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->assertions);
    }

    public function normalise(): UniqueAssertionCollection
    {
        $normalisedCollection = new UniqueAssertionCollection();

        foreach ($this as $assertion) {
            /** @var FooAssertionInterface $assertion */
            $normalisedCollection->add($assertion->normalise());
        }

        return $normalisedCollection;
    }

    private function contains(FooAssertionInterface $assertion): bool
    {
        foreach ($this->assertions as $comparator) {
            if ($assertion->equals($comparator)) {
                return true;
            }
        }

        return false;
    }
}
