<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\BrowserProperty;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\BrowserProperty\BrowserProperty;

class BrowserPropertyTest extends TestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(
        string $value,
        string $expectedProperty,
        bool $expectedIsValid
    ): void {
        $pageUrlReference = new BrowserProperty($value);

        $this->assertSame($expectedProperty, $pageUrlReference->getProperty());
        $this->assertSame($expectedIsValid, $pageUrlReference->isValid());
        $this->assertSame($value, (string) $pageUrlReference);
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'empty' => [
                'value' => '',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect prefix' => [
                'value' => '$browsers.size',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'incorrect property' => [
                'value' => '$browser.address',
                'expectedProperty' => '',
                'expectedIsValid' => false,
            ],
            'valid' => [
                'value' => '$browser.size',
                'expectedProperty' => 'size',
                'expectedIsValid' => true,
            ],
        ];
    }

    #[DataProvider('isDataProvider')]
    public function testIs(string $value, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, BrowserProperty::is($value));
    }

    /**
     * @return array<mixed>
     */
    public static function isDataProvider(): array
    {
        return [
            'empty' => [
                'value' => '',
                'expectedIs' => false,
            ],
            'incorrect prefix' => [
                'value' => '$browsers.size',
                'expectedIs' => false,
            ],
            'incorrect property' => [
                'value' => '$browser.address',
                'expectedIs' => false,
            ],
            'valid' => [
                'value' => '$browser.size',
                'expectedIs' => true,
            ],
        ];
    }
}
