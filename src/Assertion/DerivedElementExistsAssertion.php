<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class DerivedElementExistsAssertion extends Assertion implements DerivedAssertionInterface
{
    private const COMPARISON = 'exists';

    private $sourceStatement;

    public function __construct(StatementInterface $sourceStatement, string $identifier)
    {
        parent::__construct($identifier . ' ' . self::COMPARISON, $identifier, self::COMPARISON);

        $this->sourceStatement = $sourceStatement;
    }

    public function getSourceStatement(): StatementInterface
    {
        return $this->sourceStatement;
    }
}
