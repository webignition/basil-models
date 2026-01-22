<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Action;

use webignition\BasilModels\Model\Statement\StatementCollectionInterface;

/**
 * @extends \IteratorAggregate<int, ActionInterface>
 */
interface ActionCollectionInterface extends \IteratorAggregate, StatementCollectionInterface {}
