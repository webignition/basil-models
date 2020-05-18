<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedElementExistsAssertion;
use webignition\BasilModels\Assertion\DerivedIsRegExpAssertion;

class DerivedIsRegExpAssertionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $identifier = '$".selector"';

        $sourceAssertion = new ComparisonAssertion(
            '$".selector" matches "value',
            $identifier,
            'is',
            '"value"'
        );

        $derivedAssertion = new DerivedIsRegExpAssertion($sourceAssertion, $identifier);

        $this->assertSame('$".selector" is-regexp', $derivedAssertion->getSource());
        $this->assertSame('$".selector" is-regexp', (string) $derivedAssertion);
        $this->assertSame($identifier, $derivedAssertion->getIdentifier());
        $this->assertSame('is-regexp', $derivedAssertion->getComparison());
        $this->assertSame($sourceAssertion, $derivedAssertion->getSourceStatement());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param DerivedIsRegExpAssertion $derivedAssertion
     * @param array<mixed> $expectedSerialisedData
     */
    public function testJsonSerialize(DerivedIsRegExpAssertion $derivedAssertion, array $expectedSerialisedData)
    {
        $this->assertSame($expectedSerialisedData, $derivedAssertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'assertion' => [
                'derivedAssertion' => new DerivedIsRegExpAssertion(
                    new ComparisonAssertion(
                        '$".selector" matches "value',
                        '$".selector',
                        'matches',
                        '"value"'
                    ),
                    '$".selector'
                ),
                'expectedSerializedData' => [
                    'type' => 'is-regexp',
                    'source_type' => 'assertion',
                    'source' => [
                        'source' => '$".selector" matches "value',
                        'identifier' => '$".selector',
                        'comparison' => 'matches',
                        'value' => '"value"',
                    ],
                    'identifier' => '$".selector'
                ],
            ],
        ];
    }
}
