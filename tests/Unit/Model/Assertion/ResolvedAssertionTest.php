<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Assertion;

use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Assertion\ResolvedAssertion;
use webignition\BasilModels\Tests\Unit\Model\AbstractStatementTest;

class ResolvedAssertionTest extends AbstractStatementTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        AssertionInterface $sourceAssertion,
        string $identifier,
        ?string $value,
        string $expectedSource
    ): void {
        $assertion = new ResolvedAssertion($sourceAssertion, $identifier, $value);

        $this->assertSame($sourceAssertion, $assertion->getSourceStatement());
        $this->assertSame($expectedSource, $assertion->getSource());
        $this->assertSame($identifier, $assertion->getIdentifier());
        $this->assertSame($sourceAssertion->getOperator(), $assertion->getOperator());
        $this->assertSame($value, $assertion->getValue());
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'exists' => [
                'sourceAssertion' => new Assertion(
                    '$page_import_name.elements.element_name exists',
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
    public function jsonSerializeDataProvider(): array
    {
        return [
            'from exists assertion' => [
                'derivedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
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
                    ],
                ],
            ],
            'from is assertion' => [
                'derivedAssertion' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name is "value"',
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
                    ],
                ],
            ],
        ];
    }

    public function testIsComparison(): void
    {
        $isAssertion = new ResolvedAssertion(
            new Assertion('$"a" is "a"', '$"a"', 'is', '"a"'),
            '$"a"',
            '"a"'
        );

        $isNotAssertion = new ResolvedAssertion(
            new Assertion('$"a" is-not "a"', '$"a"', 'is-not', '"a"'),
            '$"a"',
            '"a"'
        );

        $existsAssertion = new ResolvedAssertion(
            new Assertion('$"a" exists', '$"a"', 'exists'),
            '$"a"'
        );

        $notExistsAssertion = new ResolvedAssertion(
            new Assertion('$"a" exists', '$"a"', 'not-exists'),
            '$"a"'
        );

        $includesAssertion = new ResolvedAssertion(
            new Assertion('$"a" includes "a"', '$"a"', 'includes', '"a"'),
            '$"a"',
            '"a"'
        );

        $excludesAssertion = new ResolvedAssertion(
            new Assertion('$"a" excludes "a"', '$"a"', 'excludes', '"a"'),
            '$"a"',
            '"a"'
        );

        $matchesAssertion = new ResolvedAssertion(
            new Assertion('$"a" matches "a"', '$"a"', 'matches', '"a"'),
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
