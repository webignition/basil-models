<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\DataParameter;

use webignition\BasilModels\DataParameter\DataParameter;

class DataParameterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $reference,
        string $expectedProperty,
        bool $expectedIsValid
    ) {
        $elementReference = new DataParameter($reference);

        $this->assertSame($expectedProperty, $elementReference->getProperty());
        $this->assertSame($expectedIsValid, $elementReference->isValid());
        $this->assertSame($reference, (string) $elementReference);
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'reference' => '',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => '$data',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => '$data.key.superfluous',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect data part value' => [
                'reference' => '$foo.key',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'valid' => [
                'reference' => '$data.key',
                'expectedProperty' => 'key',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $reference, bool $expectedIs)
    {
        $this->assertSame($expectedIs, DataParameter::is($reference));
    }

    public function isDataProvider(): array
    {
        return [
            'empty' => [
                'reference' => '',
                'expectedIs' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => '$data',
                'expectedIs' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => '$data.key.superfluous',
                'expectedIs' => false,
            ],
            'incorrect data part value' => [
                'reference' => '$foo.key',
                'expectedIs' => false,
            ],
            'valid' => [
                'reference' => '$data.key',
                'expectedIs' => true,
            ],
        ];
    }
}
