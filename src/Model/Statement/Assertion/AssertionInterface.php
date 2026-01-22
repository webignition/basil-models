<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement\Assertion;

use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\Statement\StatementInterface;

/**
 * @phpstan-type SerializedAssertion array{
 *    'statement-type': value-of<StatementType::ASSERTION>,
 *    'source': string,
 *    'identifier'?: string,
 *    'value'?: string,
 *    'operator': string
 *  }
 */
interface AssertionInterface extends StatementInterface
{
    public function getOperator(): string;

    public function equals(AssertionInterface $assertion): bool;

    public function normalise(): AssertionInterface;

    public function isComparison(): bool;
}
