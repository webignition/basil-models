<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Test;

interface NamedTestInterface extends TestInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;
}
