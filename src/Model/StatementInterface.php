<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model;

use webignition\BasilModels\Enum\StatementType;

interface StatementInterface extends \JsonSerializable
{
    public function __toString(): string;

    public function getIdentifier(): ?string;

    public function getSource(): string;

    public function getStatementType(): StatementType;

    public function getValue(): ?string;

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
