<?php

declare(strict_types=1);

namespace webignition\BasilModels;

interface StatementInterface extends \JsonSerializable
{
    public function getSource(): string;
    public function __toString(): string;

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
