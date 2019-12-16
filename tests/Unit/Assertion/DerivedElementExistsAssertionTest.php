<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedElementExistsAssertion;

class DerivedElementExistsAssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $identifier = '$".selector"';

        $sourceAssertion = new ComparisonAssertion(
            '$".selector" is "value',
            $identifier,
            'is',
            '"value"'
        );

        $derivedAssertion = new DerivedElementExistsAssertion($sourceAssertion, $identifier);

        $this->assertSame('$".selector" exists', $derivedAssertion->getSource());
        $this->assertSame($identifier, $derivedAssertion->getIdentifier());
        $this->assertSame('exists', $derivedAssertion->getComparison());
        $this->assertSame($sourceAssertion, $derivedAssertion->getSourceStatement());
    }
}
