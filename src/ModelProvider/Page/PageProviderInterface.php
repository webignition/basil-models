<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider\Page;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\ProviderInterface;
use webignition\BasilModels\Page\PageInterface;

interface PageProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): PageInterface;
}
