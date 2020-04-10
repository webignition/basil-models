<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class DerivedElementExistsAssertion extends Assertion implements DerivedAssertionInterface
{
    private const COMPARISON = 'exists';

    private $sourceStatement;
    private $identifier;

    public function __construct(StatementInterface $sourceStatement, string $identifier)
    {
        parent::__construct($identifier . ' ' . self::COMPARISON, $identifier, self::COMPARISON);

        $this->sourceStatement = $sourceStatement;
        $this->identifier = $identifier;
    }

    public function getSourceStatement(): StatementInterface
    {
        return $this->sourceStatement;
    }

    public function jsonSerialize(): array
    {
        $sourceStatementData = $this->sourceStatement->jsonSerialize();

        return [
            'source_type' => $this->sourceStatement instanceof AssertionInterface ? 'assertion' : 'action',
            'source' => $sourceStatementData,
            'identifier' => $this->identifier,
        ];
    }
}
