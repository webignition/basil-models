<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\DataSet;

use webignition\BasilModels\Model\DataSet\DataSetCollectionInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\ProviderInterface;

interface DataSetProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): DataSetCollectionInterface;
}
