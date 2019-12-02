<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\Assertion;

class AssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$".selector" exists';
        $identifier = '$".selector"';
        $comparison = 'exists';

        $assertion = new Assertion($source, $identifier, $comparison);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($comparison, $assertion->getComparison());
    }

    public function testWithIdentifier()
    {
        $originalIdentifier = '$elements.element_name';
        $newIdentifier = '.selector';

        $assertion = new Assertion('$elements.element_name exists', $originalIdentifier, 'exists');
        $mutatedAssertion = $assertion->withIdentifier($newIdentifier);

        $this->assertNotSame($assertion, $mutatedAssertion);
        $this->assertSame($originalIdentifier, $assertion->getIdentifier());
        $this->assertSame($newIdentifier, $mutatedAssertion->getIdentifier());
    }
}
