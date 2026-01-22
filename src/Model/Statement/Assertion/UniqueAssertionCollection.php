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
            $assertion = $assertion->normalise();

            if (!self::contains($uniqueAssertions, $assertion)) {
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

        return new UniqueAssertionCollection(array_merge($assertions, $this->assertions));
    }

    public function append(StatementCollectionInterface $collection): static
    {
        $assertions = [];
        foreach ($collection as $statement) {
            if ($statement instanceof AssertionInterface) {
                $assertions[] = $statement;
            }
        }

        return new UniqueAssertionCollection(array_merge($this->assertions, $assertions));
    }

    /**
     * @return \Traversable<int, AssertionInterface>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->assertions);
    }

    /**
     * @param AssertionInterface[] $assertions
     */
    private static function contains(array $assertions, AssertionInterface $assertion): bool
    {
        foreach ($assertions as $comparator) {
            if ($assertion->equals($comparator)) {
                return true;
            }
        }

        return false;
    }
}
