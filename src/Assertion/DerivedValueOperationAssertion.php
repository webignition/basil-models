<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class DerivedValueOperationAssertion extends Assertion implements DerivedAssertionInterface
{
    public const KEY_ENCAPSULATION = 'encapsulation';
    public const KEY_ENCAPSULATION_TYPE = 'type';
    public const KEY_ENCAPSULATION_SOURCE_TYPE = 'source_type';
    public const KEY_ENCAPSULATION_OPERATOR = 'operator';
    public const KEY_ENCAPSULATION_VALUE = 'value';
    public const KEY_ENCAPSULATES = 'encapsulates';

    private StatementInterface $sourceStatement;
    private string $value;

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
        return [
            self::KEY_ENCAPSULATION => [
                self::KEY_ENCAPSULATION_TYPE => 'derived-value-operation-assertion',
                self::KEY_ENCAPSULATION_SOURCE_TYPE => $this->sourceStatement instanceof AssertionInterface
                    ? 'assertion'
                    : 'action',
                self::KEY_ENCAPSULATION_OPERATOR => $this->getComparison(),
                self::KEY_ENCAPSULATION_VALUE => $this->value,
            ],
            self::KEY_ENCAPSULATES => $this->sourceStatement->jsonSerialize(),
        ];
    }
}
