<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement;

/**
 * @extends \IteratorAggregate<int, StatementInterface>
 */
interface StatementCollectionInterface extends \IteratorAggregate
{
    public function prepend(StatementCollectionInterface $collection): static;

    public function append(StatementCollectionInterface $collection): static;
}
