<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

interface SourcedTestInterface extends TestInterface
{
    /**
     * @return non-empty-string
     */
    public function getPath(): string;
}
