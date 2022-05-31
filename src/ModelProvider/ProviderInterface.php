<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;

interface ProviderInterface
{
    /**
     * @throws UnknownItemException
     *
     * @return mixed
     */
    public function find(string $name);
}
