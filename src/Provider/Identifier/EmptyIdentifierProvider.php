<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Identifier;

use webignition\BasilModels\Provider\Exception\UnknownItemException;

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
