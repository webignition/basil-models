<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\Statement;

class Action extends Statement implements ActionInterface
{
    private const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';
    private const KEY_IDENTIFIER = 'identifier';
    private const KEY_VALUE = 'value';

    private string $type;
    private ?string $arguments;
    private ?string $identifier;
    private ?string $value;

    public function __construct(
        string $source,
        string $type,
        ?string $arguments = null,
        ?string $identifier = null,
        ?string $value = null
    ) {
        parent::__construct($source);

        $this->source = $source;
        $this->type = $type;
        $this->arguments = $arguments;
        $this->identifier = $identifier;
        $this->value = $value;
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

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getValue(): ?string
    {
        return $this->value;
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

        if (null !== $this->identifier) {
            $data[self::KEY_IDENTIFIER] = $this->identifier;
        }

        if (null !== $this->value) {
            $data[self::KEY_VALUE] = $this->value;
        }

        return $data;
    }
}
