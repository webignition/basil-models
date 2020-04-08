<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

interface AssertionInterface extends StatementInterface
{
    public function getIdentifier(): string;
    public function getComparison(): string;
    public function withComparison(string $comparison): AssertionInterface;
    public function withIdentifier(string $identifier): AssertionInterface;
    public function equals(AssertionInterface $assertion): bool;
    public function normalise(): AssertionInterface;

    /**
     * @param array<mixed> $data
     *
     * @return AssertionInterface|null
     */
    public static function fromArray(array $data): ?AssertionInterface;

    public static function createsFromComparison(string $comparison): bool;
}
