<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\Statement;

class Action extends Statement implements ActionInterface
{
    public const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';

    private string $type;
    private string $arguments;

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

    public static function fromArray(array $data): ActionInterface
    {
        return new Action(
            (string) ($data[self::KEY_SOURCE] ?? ''),
            (string) ($data[self::KEY_TYPE] ?? ''),
            (string) ($data[self::KEY_ARGUMENTS] ?? '')
        );
    }
}
