<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion;

class ComparisonAssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$".selector" is "value"';
        $identifier = '$".selector"';
        $comparison = 'is';
        $value = '"value"';

        $assertion = new Assertion\ComparisonAssertion($source, $identifier, $comparison, $value);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($comparison, $assertion->getComparison());
        $this->assertSame($value, $assertion->getValue());
    }
}
