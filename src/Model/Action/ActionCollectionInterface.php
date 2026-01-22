<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

/**
 * @extends \IteratorAggregate<int, ActionInterface>
 */
interface ActionCollectionInterface extends \IteratorAggregate
{
    public function prepend(ActionCollectionInterface $collection): ActionCollectionInterface;
}
