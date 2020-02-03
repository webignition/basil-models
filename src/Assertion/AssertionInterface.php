<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

interface AssertionInterface extends StatementInterface, \JsonSerializable
{
    public function getIdentifier(): string;
    public function getComparison(): string;
    public function withIdentifier(string $identifier): AssertionInterface;
    public function equals(AssertionInterface $assertion): bool;

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
