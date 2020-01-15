<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;

class AssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $source = '$".selector" exists';
        $identifier = '$".selector"';
        $comparison = 'exists';

        $assertion = new Assertion($source, $identifier, $comparison);

        $this->assertSame($source, $assertion->getSource());
        $this->assertSame($source, (string) $assertion);
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

    /**
     * @dataProvider equalsDataProvider
     */
    public function testEquals(AssertionInterface $source, AssertionInterface $comparator, bool $expectedEquals)
    {
        $this->assertSame($expectedEquals, $source->equals($comparator));
    }

    public function equalsDataProvider(): array
    {
        return [
            'identifiers do not match' => [
                'source' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'comparator' => new Assertion('$".comparator" exists', '$".comparator"', 'exists'),
                'expectedEquals' => false,
            ],
            'comparisons do not match' => [
                'source' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'comparator' => new Assertion('$".source" not-exists', '$".source"', 'not-exists'),
                'expectedEquals' => false,
            ],
            'equal' => [
                'source' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'comparator' => new Assertion('$".source" exists', '$".source"', 'exists'),
                'expectedEquals' => true,
            ],
        ];
    }
}
