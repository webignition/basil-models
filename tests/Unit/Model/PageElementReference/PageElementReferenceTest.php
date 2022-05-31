<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\PageElementReference;

use webignition\BasilModels\Model\PageElementReference\PageElementReference;

class PageElementReferenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $reference,
        string $expectedImportName,
        string $expectedElementName,
        string $expectedAttributeName,
        bool $expectedIsValid
    ): void {
        $pageElementReference = new PageElementReference($reference);

        $this->assertSame($expectedImportName, $pageElementReference->getImportName());
        $this->assertSame($expectedElementName, $pageElementReference->getElementName());
        $this->assertSame($expectedAttributeName, $pageElementReference->getAttributeName());
        $this->assertSame($expectedIsValid, $pageElementReference->isValid());
        $this->assertSame($reference, (string) $pageElementReference);
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'reference' => '',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => 'import_name',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => 'import_name.elements',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect elements part value' => [
                'reference' => 'import_name.foo.element_name',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedAttributeName' => '',
                'expectedIsValid' => false,
            ],
            'valid element reference' => [
                'reference' => 'import_name.elements.element_name',
                'expectedImportName' => 'import_name',
                'expectedElementName' => 'element_name',
                'expectedAttributeName' => '',
                'expectedIsValid' => true,
            ],
            'valid attribute reference' => [
                'reference' => 'import_name.elements.element_name.attribute_name',
                'expectedImportName' => 'import_name',
                'expectedElementName' => 'element_name',
                'expectedAttributeName' => 'attribute_name',
                'expectedIsValid' => true,
            ],
            'valid attribute reference with dot in attribute name' => [
                'reference' => 'import_name.elements.element_name.attribute.name',
                'expectedImportName' => 'import_name',
                'expectedElementName' => 'element_name',
                'expectedAttributeName' => 'attribute.name',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $reference, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, PageElementReference::is($reference));
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
            'lacking elements part, element name' => [
                'reference' => 'import_name',
                'expectedIs' => false,
            ],
            'lacking element name' => [
                'reference' => 'import_name.elements',
                'expectedIs' => false,
            ],
            'valid element reference' => [
                'reference' => 'import_name.elements.element_name',
                'expectedIs' => true,
            ],
            'valid attribute reference' => [
                'reference' => 'import_name.elements.element_name.attribute_name',
                'expectedIs' => true,
            ],
        ];
    }
}
