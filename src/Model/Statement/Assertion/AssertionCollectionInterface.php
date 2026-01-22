<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Assertion;

/**
 * @extends \IteratorAggregate<int, AssertionInterface>
 */
interface AssertionCollectionInterface extends \IteratorAggregate
{
    public function prepend(AssertionCollectionInterface $collection): self;

    public function append(AssertionCollectionInterface $collection): self;
}
