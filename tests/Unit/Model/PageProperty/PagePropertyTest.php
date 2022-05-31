<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\PageProperty;

use webignition\BasilModels\Model\PageProperty\PageProperty;

class PagePropertyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $value,
        string $expectedProperty,
        bool $expectedIsValid
    ): void {
        $pageUrlReference = new PageProperty($value);

        $this->assertSame($expectedProperty, $pageUrlReference->getProperty());
        $this->assertSame($expectedIsValid, $pageUrlReference->isValid());
        $this->assertSame($value, (string) $pageUrlReference);
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'value' => '',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect prefix' => [
                'value' => '$pages.url',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect property' => [
                'value' => '$page.address',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'valid: url' => [
                'value' => '$page.url',
                'expectedProperty' => 'url',
                'expectedIsValid' => true,
            ],
            'valid: title' => [
                'value' => '$page.title',
                'expectedProperty' => 'title',
                'expectedIsValid' => true,
            ],
        ];
    }

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $value, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, PageProperty::is($value));
    }

    /**
     * @return array<mixed>
     */
    public function isDataProvider(): array
    {
        return [
            'empty string' => [
                'value' => '',
                'expectedIs' => false,
            ],
            'incorrect prefix' => [
                'value' => '$pages.url',
                'expectedIs' => false,
            ],
            'incorrect property' => [
                'value' => '$page.address',
                'expectedIs' => false,
            ],
            'valid: url' => [
                'value' => '$page.url',
                'expectedIs' => true,
            ],
            'valid: title' => [
                'value' => '$page.title',
                'expectedIs' => true,
            ],
        ];
    }
}
