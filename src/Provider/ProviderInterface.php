<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider;

use webignition\BasilModels\Provider\Exception\UnknownItemException;

interface ProviderInterface
{
    /**
     * @return mixed
     *
     * @throws UnknownItemException
     */
    public function find(string $name);
}
