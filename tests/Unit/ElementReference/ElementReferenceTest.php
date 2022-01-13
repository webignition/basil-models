<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\ElementReference;

use webignition\BasilModels\ElementReference\ElementReference;

class ElementReferenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $reference,
        string $expectedElementName,
        bool $expectedIsValid
    ): void {
        $elementReference = new ElementReference($reference);

        $this->assertSame($expectedElementName, $elementReference->getElementName());
        $this->assertSame($expectedIsValid, $elementReference->isValid());
        $this->assertSame($reference, (string) $elementReference);
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'reference' => '',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => '$elements',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => '$elements.element_name.another_element_name',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect elements part value' => [
                'reference' => '$foo.element_name',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'valid' => [
                'reference' => '$elements.element_name',
                'expectedElementName' => 'element_name',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $reference, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, ElementReference::is($reference));
    }

    /**
     * @return array<mixed>
     */
    public function isDataProvider(): array
    {
        return [
            'empty string' => [
                'reference' => '',
                'expectedIs' => false,
            ],
            'non-empty valid string' => [
                'reference' => 'foo',
                'expectedIs' => false,
            ],
            'lacking element name' => [
                'reference' => '$elements',
                'expectedIs' => false,
            ],
            'has more than one dot' => [
                'reference' => '$elements.element_name.name',
                'expectedIs' => false,
            ],
            'valid' => [
                'reference' => '$elements.name',
                'expectedIs' => true,
            ],
        ];
    }
}
