<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\PageElementReference;

use webignition\BasilModels\PageElementReference\PageElementReference;

class PageElementReferenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $reference,
        string $expectedImportName,
        string $expectedElementName,
        bool $expectedIsValid
    ) {
        $pageElementReference = new PageElementReference($reference);

        $this->assertSame($expectedImportName, $pageElementReference->getImportName());
        $this->assertSame($expectedElementName, $pageElementReference->getElementName());
        $this->assertSame($expectedIsValid, $pageElementReference->isValid());
        $this->assertSame($reference, (string) $pageElementReference);
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'reference' => '',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => 'import_name',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => 'import_name.elements',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (>3)' => [
                'reference' => 'import_name.elements.element_name.another_element_name',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect elements part value' => [
                'reference' => 'import_name.foo.element_name',
                'expectedImportName' => '',
                'expectedElementName' => '',
                'expectedIsValid' => false,
            ],
            'valid' => [
                'reference' => 'import_name.elements.element_name',
                'expectedImportName' => 'import_name',
                'expectedElementName' => 'element_name',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $reference, bool $expectedIs)
    {
        $this->assertSame($expectedIs, PageElementReference::is($reference));
    }

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
            'has more than two dots' => [
                'reference' => 'import_name.elements.element_name.name',
                'expectedIs' => false,
            ],
            'valid' => [
                'reference' => 'import_name.elements.element_name',
                'expectedIs' => true,
            ],
        ];
    }
}
