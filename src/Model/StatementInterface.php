<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model;

use webignition\BasilModels\Enum\StatementType;

/**
 * @phpstan-type SerializedStatement array{
 *    'statement-type': value-of<StatementType>,
 *    'source': string,
 *    'identifier'?: string,
 *    'value'?: string
 *  }
 */
interface StatementInterface extends \JsonSerializable
{
    public function __toString(): string;

    public function getIdentifier(): ?string;

    public function getSource(): string;

    public function getStatementType(): StatementType;

    public function getValue(): ?string;

    /**
     * @return SerializedStatement
     */
    public function jsonSerialize(): array;
}
