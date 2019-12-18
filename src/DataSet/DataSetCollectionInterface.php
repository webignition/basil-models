<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataSet;

/**
 * @extends \Iterator<DataSetInterface>
 */
interface DataSetCollectionInterface extends \Countable, \Iterator
{
    /**
     * @return string[]
     */
    public function getParameterNames(): array;

    // Iterator method additions
    public function current(): ?DataSetInterface;
}
