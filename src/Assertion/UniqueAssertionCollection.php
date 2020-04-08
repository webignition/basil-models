<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

/**
 * @implements \IteratorAggregate<AssertionInterface>
 */
class UniqueAssertionCollection implements \IteratorAggregate
{
    /**
     * @var AssertionInterface[]
     */
    private $assertions = [];

    public function add(AssertionInterface $assertion): void
    {
        if (!$this->contains($assertion)) {
            $this->assertions[] = $assertion;
        }
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
            /** @var AssertionInterface $assertion */
            $normalisedCollection->add($assertion->normalise());
        }

        return $normalisedCollection;
    }

    private function contains(AssertionInterface $assertion): bool
    {
        foreach ($this->assertions as $comparator) {
            if ($assertion->equals($comparator)) {
                return true;
            }
        }

        return false;
    }
}
