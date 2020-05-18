<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class DerivedValueOperationAssertion extends Assertion implements DerivedAssertionInterface
{
    public const KEY_SOURCE_TYPE = 'source_type';
    public const KEY_SOURCE = 'source';
    public const KEY_VALUE = 'value';
    public const KEY_OPERATOR = 'operator';

    private $sourceStatement;
    private $value;

    public function __construct(StatementInterface $sourceStatement, string $value, string $operator)
    {
        parent::__construct($value . ' ' . $operator, $value, $operator);

        $this->sourceStatement = $sourceStatement;
        $this->value = $value;
    }

    public function getSourceStatement(): StatementInterface
    {
        return $this->sourceStatement;
    }

    public function jsonSerialize(): array
    {
        $sourceStatement = $this->getSourceStatement();

        return [
            self::KEY_OPERATOR => $this->getComparison(),
            self::KEY_SOURCE_TYPE => $sourceStatement instanceof AssertionInterface ? 'assertion' : 'action',
            self::KEY_SOURCE => $sourceStatement->jsonSerialize(),
            self::KEY_VALUE => $this->value,
        ];
    }
}
