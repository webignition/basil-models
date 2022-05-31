<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\DataSet;

use webignition\BasilModels\Model\DataSet\DataSetCollectionInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;

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
