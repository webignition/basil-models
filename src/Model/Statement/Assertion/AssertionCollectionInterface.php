<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Assertion;

use webignition\BasilModels\Model\Statement\StatementCollectionInterface;

/**
 * @extends \IteratorAggregate<int, AssertionInterface>
 */
interface AssertionCollectionInterface extends \IteratorAggregate, StatementCollectionInterface {}
