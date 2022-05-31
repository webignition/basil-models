<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider\DataSet;

use webignition\BasilModels\DataSet\DataSetCollectionInterface;
use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\ProviderInterface;

interface DataSetProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): DataSetCollectionInterface;
}
