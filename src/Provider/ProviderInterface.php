<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider;

use webignition\BasilModels\Provider\Exception\UnknownItemException;

interface ProviderInterface
{
    /**
     * @throws UnknownItemException
     *
     * @return mixed
     */
    public function find(string $name);
}
