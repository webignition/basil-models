<?php

declare(strict_types=1);

namespace webignition\BasilModels;

interface FooStatementInterface extends \JsonSerializable
{
    public function getSource(): string;
    public function getStatementType(): string;
    public function __toString(): string;

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
