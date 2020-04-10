<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Assertion;

use webignition\BasilModels\Action\InteractionAction;
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
        $this->assertSame('$".selector" exists', (string) $derivedAssertion);
        $this->assertSame($identifier, $derivedAssertion->getIdentifier());
        $this->assertSame('exists', $derivedAssertion->getComparison());
        $this->assertSame($sourceAssertion, $derivedAssertion->getSourceStatement());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     */
    public function testJsonSerialize(DerivedElementExistsAssertion $derivedAssertion, array $expectedSerialisedData)
    {
        $this->assertSame($expectedSerialisedData, $derivedAssertion->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'assertion' => [
                'derivedAssertion' => new DerivedElementExistsAssertion(
                    new ComparisonAssertion(
                        '$".selector" is "value',
                        '$".selector',
                        'is',
                        '"value"'
                    ),
                    '$".selector'
                ),
                'expectedSerializedData' => [
                    'source_type' => 'assertion',
                    'source' => [
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector',
                        'comparison' => 'is',
                        'value' => '"value"',
                    ],
                    'identifier' => '$".selector'
                ],
            ],
            'action' => [
                'derivedAssertion' => new DerivedElementExistsAssertion(
                    new InteractionAction(
                        'click $".selector"',
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"'
                ),
                'expectedSerializedData' => [
                    'source_type' => 'action',
                    'source' => [
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                    ],
                    'identifier' => '$".selector"'
                ],
            ],
        ];
    }
}
