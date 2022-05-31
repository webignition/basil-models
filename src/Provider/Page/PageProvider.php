<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Page;

use webignition\BasilModels\Model\Page\PageInterface;
use webignition\BasilModels\Provider\Exception\UnknownItemException;

class PageProvider implements PageProviderInterface
{
    /**
     * @var PageInterface[]
     */
    private array $items = [];

    /**
     * @param array<mixed> $pages
     */
    public function __construct(array $pages)
    {
        foreach ($pages as $importName => $page) {
            if ($page instanceof PageInterface) {
                $this->items[$importName] = $page;
            }
        }
    }

    /**
     * @throws UnknownItemException
     */
    public function find(string $name): PageInterface
    {
        $page = $this->items[$name] ?? null;

        if (null === $page) {
            throw new UnknownItemException(UnknownItemException::TYPE_PAGE, $name);
        }

        return $page;
    }
}
