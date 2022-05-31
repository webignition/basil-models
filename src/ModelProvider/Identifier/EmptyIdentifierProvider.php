<?php

declare(strict_types=1);

namespace webignition\BasilModels\ModelProvider\Identifier;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;

class EmptyIdentifierProvider implements IdentifierProviderInterface
{
    /**
     * @throws UnknownItemException
     */
    public function find(string $name): string
    {
        throw new UnknownItemException(UnknownItemException::TYPE_IDENTIFIER, $name);
    }
}
