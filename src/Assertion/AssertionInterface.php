<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

interface AssertionInterface extends StatementInterface
{
    public function getIdentifier(): string;
    public function getComparison(): string;
}