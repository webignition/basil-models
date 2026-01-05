<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\Statement;

/**
 * @phpstan-import-type SerializedAction from ActionInterface
 */
class Action extends Statement implements ActionInterface
{
    private const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';

    public function __construct(
        string $source,
        int $index,
        private readonly string $type,
        private readonly ?string $arguments = null,
        ?string $identifier = null,
        ?string $value = null
    ) {
        parent::__construct($source, $index, $identifier, $value);
    }

    /**
     * @return StatementType::ACTION
     */
    public function getStatementType(): StatementType
    {
        return StatementType::ACTION;
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
     * @return SerializedAction
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        \assert($data['statement-type'] === $this->getStatementType()->value);

        $data[self::KEY_TYPE] = $this->type;

        if (null !== $this->arguments) {
            $data[self::KEY_ARGUMENTS] = $this->arguments;
        }

        return $data;
    }
}
