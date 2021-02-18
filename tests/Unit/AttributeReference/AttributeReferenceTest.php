<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\AttributeReference;

use webignition\BasilModels\AttributeReference\AttributeReference;

class AttributeReferenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $reference,
        string $expectedElementName,
        string $expectedAttributeName,
        bool $expectedIsValid
    ): void {
        $attributeReference = new AttributeReference($reference);

        $this->assertSame($expectedElementName, $attributeReference->getElementName());
        $this->assertSame($expectedAttributeName, $attributeReference->getAttributeName());
        $this->assertSame($expectedIsValid, $attributeReference->isValid());
        $this->assertSame($reference, (string) $attributeReference);
    }

    /**
     * @return array[]
     */
    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'reference' => '',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => '$elements',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => '$elements.element_name.attribute_name.superfluous',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect elements part value' => [
                'reference' => '$foo.element_name.attribute_name',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'valid' => [
                'reference' => '$elements.element_name.attribute_name',
                'expectedElementName' => 'element_name',
                'expectedAttributeName' => 'attribute_name',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $reference, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, AttributeReference::is($reference));
    }

    /**
     * @return array[]
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
            'lacking attribute name' => [
                'reference' => '$elements.element_name',
                'expectedIs' => false,
            ],
            'has more than two dots' => [
                'reference' => '$elements.element_name.attribute_name.superfluous',
                'expectedIs' => false,
            ],
            'valid' => [
                'reference' => '$elements.element_name.attribute_name',
                'expectedIs' => true,
            ],
        ];
    }
}
