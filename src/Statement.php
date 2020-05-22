<?php

declare(strict_types=1);

namespace webignition\BasilModels;

abstract class Statement implements StatementInterface
{
    protected const KEY_STATEMENT_TYPE = 'statement-type';
    protected const KEY_SOURCE = 'source';

    protected string $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function __toString(): string
    {
        return $this->source;
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_STATEMENT_TYPE => $this->getStatementType(),
            self::KEY_SOURCE => $this->source,
        ];
    }
}
