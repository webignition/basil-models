<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataSet;

interface DataSetCollectionInterface extends \Countable, \Iterator
{
    /**
     * @return string[]
     */
    public function getParameterNames(): array;

    // Iterator method additions
    public function current(): ?DataSetInterface;
}
