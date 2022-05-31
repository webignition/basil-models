<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Test;

use webignition\BasilModels\Model\Test\Configuration;
use webignition\BasilModels\Model\Test\ConfigurationInterface;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getBrowserDataProvider
     */
    public function testGetBrowser(ConfigurationInterface $configuration, string $expectedBrowser): void
    {
        self::assertSame($expectedBrowser, $configuration->getBrowser());
    }

    /**
     * @return array<mixed>
     */
    public function getBrowserDataProvider(): array
    {
        return [
            'empty' => [
                'configuration' => new Configuration('', ''),
                'expectedBrowser' => '',
            ],
            'non-empty' => [
                'configuration' => new Configuration('chrome', ''),
                'expectedBrowser' => 'chrome',
            ],
        ];
    }

    /**
     * @dataProvider getUrlDataProvider
     */
    public function testGetUrl(ConfigurationInterface $configuration, string $expectedUrl): void
    {
        self::assertSame($expectedUrl, $configuration->getUrl());
    }

    /**
     * @return array<mixed>
     */
    public function getUrlDataProvider(): array
    {
        return [
            'empty' => [
                'configuration' => new Configuration('', ''),
                'expectedUrl' => '',
            ],
            'non-empty' => [
                'configuration' => new Configuration('', 'http://example.com/'),
                'expectedUrl' => 'http://example.com/',
            ],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(ConfigurationInterface $configuration, int $expectedValidationState): void
    {
        self::assertSame($expectedValidationState, $configuration->validate());
    }

    /**
     * @return array<mixed>
     */
    public function validateDataProvider(): array
    {
        return [
            'browser empty' => [
                'configuration' => new Configuration('', ''),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_BROWSER_EMPTY,
            ],
            'browser whitespace only' => [
                'configuration' => new Configuration('   ', ''),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_BROWSER_EMPTY,
            ],
            'url empty' => [
                'configuration' => new Configuration('chrome', ''),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_URL_EMPTY,
            ],
            'url whitespace only' => [
                'configuration' => new Configuration('chrome', '  '),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_URL_EMPTY,
            ],
            'valid' => [
                'configuration' => new Configuration('chrome', 'http://example.com.'),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
