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
    ) {
        $pageElementReference = new ElementReference($reference);

        $this->assertSame($expectedElementName, $pageElementReference->getElementName());
        $this->assertSame($expectedIsValid, $pageElementReference->isValid());
        $this->assertSame($reference, (string) $pageElementReference);
    }

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
    public function testIs(string $elementReference, bool $expectedIs)
    {
        $this->assertSame($expectedIs, ElementReference::is($elementReference));
    }

    public function isDataProvider(): array
    {
        return [
            'empty string' => [
                'elementReference' => '',
                'expectedIs' => false,
            ],
            'non-empty valid string' => [
                'elementReference' => 'foo',
                'expectedIs' => false,
            ],
            'lacking element name (1)' => [
                'elementReference' => '$elements',
                'expectedIs' => false,
            ],
            'lacking element name (2)' => [
                'elementReference' => '$elements',
                'expectedIs' => false,
            ],
            'has more than one dot' => [
                'elementReference' => '$elements.element_name.name',
                'expectedIs' => false,
            ],
            'valid' => [
                'elementReference' => '$elements.name',
                'expectedIs' => true,
            ],
        ];
    }
}
