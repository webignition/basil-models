<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Page;

use webignition\BasilModels\Model\Page\PageInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;

class EmptyPageProvider implements PageProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): PageInterface
    {
        throw new UnknownItemException(UnknownItemException::TYPE_PAGE, $name);
    }
}
