<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Statement\Assertion\Assertion;
use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilModels\Parser\Exception\UnparseableAssertionException;

class AssertionParserTest extends TestCase
{
    private AssertionParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = AssertionParser::create();
    }

    #[DataProvider('parseDataProvider')]
    public function testParse(string $assertionString, int $index, AssertionInterface $expectedAssertion): void
    {
        $parser = AssertionParser::create();

        $this->assertEquals($expectedAssertion, $parser->parse($assertionString, $index));
    }

    /**
     * @return array<mixed>
     */
    public static function parseDataProvider(): array
    {
        return [
            'css element selector, is, scalar value, index=0' => [
                'assertionString' => '$".selector" is "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'css element selector, is, scalar value, index=1' => [
                'assertionString' => '$".selector" is "value"',
                'index' => 1,
                'expectedAssertion' => new Assertion(
                    '$".selector" is "value"',
                    1,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'css parent > child element selector, is, scalar value' => [
                'assertionString' => '$".parent" >> $".child" is "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".parent" >> $".child" is "value"',
                    0,
                    '$".parent" >> $".child"',
                    'is',
                    '"value"'
                ),
            ],
            'css grandparent > parent > child element selector, is, scalar value' => [
                'assertionString' => '$".grandparent" >> $".parent" >> $".child" is "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".grandparent" >> $".parent" >> $".child" is "value"',
                    0,
                    '$".grandparent" >> $".parent" >> $".child"',
                    'is',
                    '"value"'
                ),
            ],
            'css element selector containing whitespace, is, scalar value' => [
                'assertionString' => '$".parent .child" is "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".parent .child" is "value"',
                    0,
                    '$".parent .child"',
                    'is',
                    '"value"'
                ),
            ],
            'css element selector, is-not, scalar value' => [
                'assertionString' => '$".selector" is-not "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" is-not "value"',
                    0,
                    '$".selector"',
                    'is-not',
                    '"value"'
                ),
            ],
            'css attribute selector, is, scalar value' => [
                'assertionString' => '$".selector".attribute_name is "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector".attribute_name is "value"',
                    0,
                    '$".selector".attribute_name',
                    'is',
                    '"value"'
                ),
            ],
            'scalar value, is, scalar value' => [
                'assertionString' => '"value" is "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '"value" is "value"',
                    0,
                    '"value"',
                    'is',
                    '"value"'
                ),
            ],
            'css element selector, is, dom identifier value' => [
                'assertionString' => '$".selector1" is $".selector2"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector1" is $".selector2"',
                    0,
                    '$".selector1"',
                    'is',
                    '$".selector2"'
                ),
            ],
            'css element selector, is, descendant dom identifier value' => [
                'assertionString' => '$".selector1" is $".parent" >> $".child"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector1" is $".parent" >> $".child"',
                    0,
                    '$".selector1"',
                    'is',
                    '$".parent" >> $".child"'
                ),
            ],
            'css element selector, is, nested descendant dom identifier value' => [
                'assertionString' => '$".selector1" is $".grandparent" >> $".parent" >> $".child"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector1" is $".grandparent" >> $".parent" >> $".child"',
                    0,
                    '$".selector1"',
                    'is',
                    '$".grandparent" >> $".parent" >> $".child"'
                ),
            ],
            'css element selector, exists, no value' => [
                'assertionString' => '$".selector" exists',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" exists',
                    0,
                    '$".selector"',
                    'exists'
                ),
            ],
            'css element selector, not-exists, no value' => [
                'assertionString' => '$".selector" not-exists',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" not-exists',
                    0,
                    '$".selector"',
                    'not-exists'
                ),
            ],
            'css element selector, exists, scalar value' => [
                'assertionString' => '$".selector" exists "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" exists "value"',
                    0,
                    '$".selector"',
                    'exists'
                ),
            ],
            'css selector, includes, scalar value' => [
                'assertionString' => '$".selector" includes "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" includes "value"',
                    0,
                    '$".selector"',
                    'includes',
                    '"value"'
                ),
            ],
            'css selector, excludes, scalar value' => [
                'assertionString' => '$".selector" excludes "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" excludes "value"',
                    0,
                    '$".selector"',
                    'excludes',
                    '"value"'
                ),
            ],
            'css selector, matches, scalar value' => [
                'assertionString' => '$".selector" matches "value"',
                'index' => 0,
                'expectedAssertion' => new Assertion(
                    '$".selector" matches "value"',
                    0,
                    '$".selector"',
                    'matches',
                    '"value"'
                ),
            ],
        ];
    }

    public function testParseEmptyAssertion(): void
    {
        $this->expectExceptionObject(UnparseableAssertionException::createEmptyAssertionException());

        $this->parser->parse('', 0);
    }

    public function testParseEmptyIdentifier(): void
    {
        $source = 'foo';

        $this->expectExceptionObject(UnparseableAssertionException::createEmptyIdentifierException($source));

        $this->parser->parse($source, 0);
    }

    public function testParseEmptyComparison(): void
    {
        $source = '$page.title';

        $this->expectExceptionObject(UnparseableAssertionException::createEmptyComparisonException($source));

        $this->parser->parse($source, 0);
    }

    public function testParseEmptyValue(): void
    {
        $source = '$page.title is';

        $this->expectExceptionObject(UnparseableAssertionException::createEmptyValueException($source));

        $this->parser->parse($source, 0);
    }

    public function testParseInvalidValueFormat(): void
    {
        $source = '$page.title is value';

        $this->expectExceptionObject(UnparseableAssertionException::createInvalidValueFormatException($source));

        $this->parser->parse($source, 0);
    }
}
