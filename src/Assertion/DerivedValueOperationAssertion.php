<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class DerivedValueOperationAssertion extends Assertion implements DerivedAssertionInterface
{
    public const KEY_ENCAPSULATION_OPERATOR = 'operator';
    public const KEY_ENCAPSULATION_VALUE = 'value';

    private string $value;

    private EncapsulatingAssertionData $encapsulatingAssertionData;

    public function __construct(StatementInterface $sourceStatement, string $value, string $operator)
    {
        parent::__construct($value . ' ' . $operator, $value, $operator);

        $this->encapsulatingAssertionData = new EncapsulatingAssertionData(
            $sourceStatement,
            'derived-value-operation-assertion',
            [
                self::KEY_ENCAPSULATION_OPERATOR => $operator,
                self::KEY_ENCAPSULATION_VALUE => $value,
            ]
        );

        $this->value = $value;
    }

    public function normalise(): AssertionInterface
    {
        return $this;
    }

    public function getSourceStatement(): StatementInterface
    {
        return $this->encapsulatingAssertionData->getStatement();
    }

    public function jsonSerialize(): array
    {
        return $this->encapsulatingAssertionData->jsonSerialize();
    }
}
