<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\BrowserProperty;

use webignition\BasilModels\BrowserProperty\BrowserProperty;

class BrowserPropertyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
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
     * @return array[]
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

    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $value, bool $expectedIs): void
    {
        $this->assertSame($expectedIs, BrowserProperty::is($value));
    }

    /**
     * @return array[]
     */
    public function isDataProvider(): array
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
