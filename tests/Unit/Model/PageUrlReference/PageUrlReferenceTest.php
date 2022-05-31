<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\PageUrlReference;

use webignition\BasilModels\Model\PageUrlReference\PageUrlReference;

class PageUrlReferenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $reference,
        string $expectedImportName,
        bool $expectedIsValid
    ): void {
        $pageUrlReference = new PageUrlReference($reference);

        $this->assertSame($expectedImportName, $pageUrlReference->getImportName());
        $this->assertSame($expectedIsValid, $pageUrlReference->isValid());
        $this->assertSame($reference, (string) $pageUrlReference);
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
                'expectedIsValid' => false,
            ],
            'incorrect part count (1)' => [
                'reference' => '$import_name',
                'expectedImportName' => '',
                'expectedIsValid' => false,
            ],
            'incorrect part count (2)' => [
                'reference' => '$import_name.url.superfluous',
                'expectedImportName' => '',
                'expectedIsValid' => false,
            ],
            'valid' => [
                'reference' => '$import_name.url',
                'expectedImportName' => 'import_name',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $reference, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, PageUrlReference::is($reference));
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
            'lacking url part' => [
                'reference' => '$import_name',
                'expectedIs' => false,
            ],
            'has more than one dot' => [
                'reference' => '$import_name.url.superfluous',
                'expectedIs' => false,
            ],
            'valid' => [
                'reference' => '$import_name.url',
                'expectedIs' => true,
            ],
        ];
    }
}
