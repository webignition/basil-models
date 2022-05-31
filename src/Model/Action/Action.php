<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

use webignition\BasilModels\Model\Statement;

class Action extends Statement implements ActionInterface
{
    private const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';

    public function __construct(
        string $source,
        private readonly string $type,
        private readonly ?string $arguments = null,
        ?string $identifier = null,
        ?string $value = null
    ) {
        parent::__construct($source, $identifier, $value);
    }

    public function getStatementType(): string
    {
        return 'action';
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getArguments(): ?string
    {
        return $this->arguments;
    }

    public static function isBrowserOperationType(string $type): bool
    {
        return in_array($type, ['back', 'forward', 'reload']);
    }

    public static function isInteractionType(string $type): bool
    {
        return in_array($type, ['click', 'submit', 'wait-for']);
    }

    public static function isInputType(string $type): bool
    {
        return 'set' === $type;
    }

    public static function isWaitType(string $type): bool
    {
        return 'wait' === $type;
    }

    public function isBrowserOperation(): bool
    {
        return self::isBrowserOperationType($this->type);
    }

    public function isInteraction(): bool
    {
        return self::isInteractionType($this->type);
    }

    public function isInput(): bool
    {
        return self::isInputType($this->type);
    }

    public function isWait(): bool
    {
        return self::isWaitType($this->type);
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data[self::KEY_TYPE] = $this->type;

        if (null !== $this->arguments) {
            $data[self::KEY_ARGUMENTS] = $this->arguments;
        }

        return $data;
    }
}
