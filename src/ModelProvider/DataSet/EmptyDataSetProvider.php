<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider\DataSet;

use webignition\BasilModels\DataSet\DataSetCollectionInterface;
use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;

class EmptyDataSetProvider implements DataSetProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): DataSetCollectionInterface
    {
        throw new UnknownItemException(UnknownItemException::TYPE_DATASET, $name);
    }
}
