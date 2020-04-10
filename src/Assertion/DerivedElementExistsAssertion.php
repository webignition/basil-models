<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class DerivedElementExistsAssertion extends Assertion implements DerivedAssertionInterface
{
    public const KEY_SOURCE_TYPE = 'source_type';
    public const KEY_SOURCE = 'source';
    public const KEY_IDENTIFIER = 'identifier';

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
            self::KEY_SOURCE_TYPE => $this->sourceStatement instanceof AssertionInterface ? 'assertion' : 'action',
            self::KEY_SOURCE => $sourceStatementData,
            self::KEY_IDENTIFIER => $this->identifier,
        ];
    }
}
