<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider\Identifier;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\ProviderInterface;

interface IdentifierProviderInterface extends ProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): string;
}
