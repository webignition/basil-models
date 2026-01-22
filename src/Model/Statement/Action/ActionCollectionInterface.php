<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Action;

/**
 * @extends \IteratorAggregate<int, ActionInterface>
 */
interface ActionCollectionInterface extends \IteratorAggregate
{
    public function prepend(ActionCollectionInterface $collection): ActionCollectionInterface;
}
