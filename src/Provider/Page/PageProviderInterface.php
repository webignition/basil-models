<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Page;

use webignition\BasilModels\Model\Page\PageInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\ProviderInterface;

interface PageProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): PageInterface;
}
