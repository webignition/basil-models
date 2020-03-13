<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\Statement;

class Action extends Statement implements ActionInterface
{
    private const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';

    private $type;
    private $arguments;

    public function __construct(string $source, string $type, string $arguments)
    {
        parent::__construct($source);

        $this->type = $type;
        $this->arguments = $arguments;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getArguments(): string
    {
        return $this->arguments;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_TYPE => $this->type,
            self::KEY_ARGUMENTS => $this->arguments,
        ]);
    }

    public static function fromArray(array $data): ?ActionInterface
    {
        $source = $data[self::KEY_SOURCE] ?? null;
        $type = $data[self::KEY_TYPE] ?? null;
        $arguments = $data[self::KEY_ARGUMENTS] ?? null;

        if (null === $source || null === $type || null === $arguments) {
            return null;
        }

        return new Action((string) $source, (string) $type, (string) $arguments);
    }
}
