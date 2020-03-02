<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

class Action implements ActionInterface
{
    private const KEY_SOURCE = 'source';
    private const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';

    private $source;
    private $type;
    private $arguments;

    public function __construct(string $source, string $type, string $arguments)
    {
        $this->source = $source;
        $this->type = $type;
        $this->arguments = $arguments;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getArguments(): string
    {
        return $this->arguments;
    }

    public function __toString(): string
    {
        return $this->source;
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_SOURCE => $this->source,
            self::KEY_TYPE => $this->type,
            self::KEY_ARGUMENTS => $this->arguments,
        ];
    }
}
