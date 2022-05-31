<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Identifier;

use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\ProviderInterface;

interface IdentifierProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): string;
}
