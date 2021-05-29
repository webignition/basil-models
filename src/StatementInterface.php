<?php

declare(strict_types=1);

namespace webignition\BasilModels;

interface StatementInterface extends \JsonSerializable
{
    public function __toString(): string;

    public function getSource(): string;

    public function getStatementType(): string;

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
