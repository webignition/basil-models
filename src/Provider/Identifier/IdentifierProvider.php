<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Identifier;

use webignition\BasilModels\Provider\Exception\UnknownItemException;

class IdentifierProvider implements IdentifierProviderInterface
{
    /**
     * @var string[]
     */
    private array $items = [];

    /**
     * @param string[] $identifiers
     */
    public function __construct(array $identifiers)
    {
        foreach ($identifiers as $name => $identifier) {
            if (is_string($identifier)) {
                $this->items[$name] = $identifier;
            }
        }
    }

    /**
     * @throws UnknownItemException
     */
    public function find(string $name): string
    {
        $identifier = $this->items[$name] ?? null;

        if (null === $identifier) {
            throw new UnknownItemException(UnknownItemException::TYPE_IDENTIFIER, $name);
        }

        return $identifier;
    }
}
