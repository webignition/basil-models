<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Assertion;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Assertion\ResolvedAssertion;
use webignition\BasilModels\Tests\Unit\Model\AbstractStatementTestCase;

class ResolvedAssertionTest extends AbstractStatementTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(
        AssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value,
        string $expectedSource
    ): void {
        $assertion = new ResolvedAssertion($sourceAssertion, $identifier, $value);

        $this->assertSame($sourceAssertion, $assertion->getSourceStatement());
        $this->assertSame($sourceAssertion->getIndex(), $assertion->getIndex());
        $this->assertSame($expectedSource, $assertion->getSource());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($sourceAssertion->getOperator(), $assertion->getOperator());
        $this->assertSame($value, $assertion->getValue());
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'exists' => [
                'sourceAssertion' => new Assertion(
                    '$page_import_name.elements.element_name exists',
                    0,
                    '$page_import_name.elements.element_name',
                    'exists'
                ),
                'identifier' => '$".resolved"',
                'value' => null,
                'expectedSource' => '$".resolved" exists',
            ],
            'is, scalar value' => [
                'sourceAssertion' => new Assertion(
                    '$page_import_name.elements.element_name is "value"',
                    0,
                    '$page_import_name.elements.element_name',
                    'is',
                    '"value"'
                ),
                'identifier' => '$".resolved"',
                'value' => '"value"',
                'expectedSource' => '$".resolved" is "value"',
            ],
            'is, elemental value' => [
                'sourceAssertion' => new Assertion(
                    '$page_import_name.elements.element_name is $page_import_name.elements.value',
                    0,
                    '$page_import_name.elements.element_name',
                    'is',
                    '$page_import_name.elements.value'
                ),
                'identifier' => '$".resolved"',
                'value' => '$".value"',
                'expectedSource' => '$".resolved" is $".value"',
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function jsonSerializeDataProvider(): array
    {
        return [
            'from exists assertion, index=0' => [
                'statement' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        0,
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-assertion',
                        'identifier' => '$".selector"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name exists',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'exists',
                        'index' => 0,
                    ],
                ],
            ],
            'from exists assertion, index=7' => [
                'statement' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        7,
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-assertion',
                        'identifier' => '$".selector"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name exists',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'exists',
                        'index' => 7,
                    ],
                ],
            ],
            'from is assertion' => [
                'statement' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name is "value"',
                        0,
                        '$page_import_name.elements.element_name',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    '"value"'
                ),
                'expectedSerializedData' => [
                    'container' => [
                        'type' => 'resolved-assertion',
                        'identifier' => '$".selector"',
                        'value' => '"value"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name is "value"',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'is',
                        'value' => '"value"',
                        'index' => 0,
                    ],
                ],
            ],
        ];
    }

    public function testIsComparison(): void
    {
        $isAssertion = new ResolvedAssertion(
            new Assertion('$"a" is "a"', 0, '$"a"', 'is', '"a"'),
            '$"a"',
            '"a"'
        );

        $isNotAssertion = new ResolvedAssertion(
            new Assertion('$"a" is-not "a"', 0, '$"a"', 'is-not', '"a"'),
            '$"a"',
            '"a"'
        );

        $existsAssertion = new ResolvedAssertion(
            new Assertion('$"a" exists', 0, '$"a"', 'exists'),
            '$"a"'
        );

        $notExistsAssertion = new ResolvedAssertion(
            new Assertion('$"a" exists', 0, '$"a"', 'not-exists'),
            '$"a"'
        );

        $includesAssertion = new ResolvedAssertion(
            new Assertion('$"a" includes "a"', 0, '$"a"', 'includes', '"a"'),
            '$"a"',
            '"a"'
        );

        $excludesAssertion = new ResolvedAssertion(
            new Assertion('$"a" excludes "a"', 0, '$"a"', 'excludes', '"a"'),
            '$"a"',
            '"a"'
        );

        $matchesAssertion = new ResolvedAssertion(
            new Assertion('$"a" matches "a"', 0, '$"a"', 'matches', '"a"'),
            '$"a"',
            '"a"'
        );

        $this->assertTrue($isAssertion->isComparison());
        $this->assertTrue($isNotAssertion->isComparison());
        $this->assertFalse($existsAssertion->isComparison());
        $this->assertFalse($notExistsAssertion->isComparison());
        $this->assertTrue($includesAssertion->isComparison());
        $this->assertTrue($excludesAssertion->isComparison());
        $this->assertTrue($matchesAssertion->isComparison());
    }
}
