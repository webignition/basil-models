<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\DataSet;

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

    /**
     * @return array<string, array<int|string, string>>
     */
    public function toArray(): array;
}
