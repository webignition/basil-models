<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

interface ImportsInterface
{
    /**
     * @return string[]
     */
    public function getStepPaths(): array;

    /**
     * @return string[]
     */
    public function getPagePaths(): array;

    /**
     * @return string[]
     */
    public function getDataProviderPaths(): array;
}
