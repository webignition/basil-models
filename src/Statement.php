<?php

declare(strict_types=1);

namespace webignition\BasilModels;

abstract class Statement implements StatementInterface, \Stringable
{
    protected const KEY_STATEMENT_TYPE = 'statement-type';
    protected const KEY_SOURCE = 'source';
    private const KEY_IDENTIFIER = 'identifier';
    private const KEY_VALUE = 'value';

    public function __construct(
        protected string $source,
        private ?string $identifier,
        private ?string $value
    ) {
    }

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

    public function jsonSerialize(): array
    {
        $data = [
            self::KEY_STATEMENT_TYPE => $this->getStatementType(),
            self::KEY_SOURCE => $this->source,
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
