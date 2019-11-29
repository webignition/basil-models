<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Test;

use webignition\BasilModels\Test\Configuration;
use webignition\BasilModels\Test\ConfigurationInterface;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getBrowserDataProvider
     */
    public function testGetBrowser(ConfigurationInterface $configuration, string $expectedBrowser)
    {
        $this->assertSame($expectedBrowser, $configuration->getBrowser());
    }

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
    public function testGetUrl(ConfigurationInterface $configuration, string $expectedUrl)
    {
        $this->assertSame($expectedUrl, $configuration->getUrl());
    }

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
}
