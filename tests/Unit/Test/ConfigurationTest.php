<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Test;

use webignition\BasilModels\Test\Configuration;
use webignition\BasilModels\Test\ConfigurationInterface;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getBrowsersDataProvider
     *
     * @param ConfigurationInterface $configuration
     * @param string[] $expectedBrowsers
     */
    public function testGetBrowsers(ConfigurationInterface $configuration, array $expectedBrowsers)
    {
        self::assertSame($expectedBrowsers, $configuration->getBrowsers());
    }

    public function getBrowsersDataProvider(): array
    {
        return [
            'empty' => [
                'configuration' => new Configuration([], ''),
                'expectedBrowsers' => [],
            ],
            'non-empty, single browser' => [
                'configuration' => new Configuration(['chrome'], ''),
                'expectedBrowsers' => ['chrome'],
            ],
            'non-empty, multiple browsers' => [
                'configuration' => new Configuration(['chrome', 'firefox'], ''),
                'expectedBrowsers' => ['chrome', 'firefox'],
            ],
        ];
    }

    /**
     * @dataProvider getUrlDataProvider
     */
    public function testGetUrl(ConfigurationInterface $configuration, string $expectedUrl)
    {
        self::assertSame($expectedUrl, $configuration->getUrl());
    }

    public function getUrlDataProvider(): array
    {
        return [
            'empty' => [
                'configuration' => new Configuration([], ''),
                'expectedUrl' => '',
            ],
            'non-empty' => [
                'configuration' => new Configuration([], 'http://example.com/'),
                'expectedUrl' => 'http://example.com/',
            ],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(ConfigurationInterface $configuration, int $expectedValidationState)
    {
        self::assertSame($expectedValidationState, $configuration->validate());
    }

    public function validateDataProvider(): array
    {
        return [
            'browser empty' => [
                'configuration' => new Configuration([], ''),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_BROWSER_EMPTY,
            ],
            'browser whitespace only' => [
                'configuration' => new Configuration(['   '], ''),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_BROWSER_EMPTY,
            ],
            'url empty' => [
                'configuration' => new Configuration(['chrome'], ''),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_URL_EMPTY,
            ],
            'url whitespace only' => [
                'configuration' => new Configuration(['chrome'], '  '),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_URL_EMPTY,
            ],
            'valid' => [
                'configuration' => new Configuration(['chrome'], 'http://example.com.'),
                'expectedValidationState' => ConfigurationInterface::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
