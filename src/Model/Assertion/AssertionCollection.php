<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

final readonly class AssertionCollection implements AssertionCollectionInterface
{
    /**
     * @param AssertionInterface[] $assertions
     */
    public function __construct(
        private array $assertions,
    ) {}

    public function prepend(AssertionCollectionInterface $collection): self
    {
        $assertions = [];
        foreach ($collection as $assertion) {
            $assertions[] = $assertion;
        }

        return new AssertionCollection(array_merge($assertions, $this->assertions));
    }

    public function append(AssertionCollectionInterface $collection): self
    {
        $new = new AssertionCollection($this->assertions);
        foreach ($collection as $assertion) {
            $new = $new->add($assertion);
        }

        return $new;
    }

    /**
     * @return \Traversable<int, AssertionInterface>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->assertions);
    }

    private function add(AssertionInterface $assertion): self
    {
        $assertions = $this->assertions;
        $assertions[] = $assertion;

        return new AssertionCollection($assertions);
    }
}
