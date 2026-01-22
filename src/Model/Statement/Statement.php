<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement;

/**
 * @phpstan-import-type SerializedStatement from StatementInterface
 */
abstract class Statement implements StatementInterface, \Stringable
{
    protected const string KEY_STATEMENT_TYPE = 'statement-type';
    protected const string KEY_SOURCE = 'source';
    private const string KEY_IDENTIFIER = 'identifier';
    private const string KEY_VALUE = 'value';
    private const string KEY_INDEX = 'index';

    public function __construct(
        private readonly string $source,
        private readonly int $index,
        private readonly ?string $identifier,
        private readonly ?string $value
    ) {}

    public function __toString(): string
    {
        return $this->source;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return SerializedStatement
     */
    public function jsonSerialize(): array
    {
        $data = [
            self::KEY_STATEMENT_TYPE => $this->getStatementType()->value,
            self::KEY_SOURCE => $this->source,
            self::KEY_INDEX => $this->index,
        ];

        $identifier = $this->getIdentifier();
        if (null !== $identifier) {
            $data[self::KEY_IDENTIFIER] = $identifier;
        }

        $value = $this->getValue();
        if (null !== $value) {
            $data[self::KEY_VALUE] = $value;
        }

        return $data;
    }
}
