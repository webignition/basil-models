<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Test;

use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Test\Imports;

class ImportsTest extends TestCase
{
    public function testGetWithStepPaths(): void
    {
        $imports = new Imports();
        $this->assertSame([], $imports->getStepPaths());

        $paths = [
            'invalid bool' => true,
            'invalid int' => 7,
            'step_name' => '/absolute/path/to/step.yml',
        ];

        $expectedPaths = [
            'step_name' => '/absolute/path/to/step.yml',
        ];

        $imports = $imports->withStepPaths($paths);
        $this->assertSame($expectedPaths, $imports->getStepPaths());
    }

    public function testGetWithPagePaths(): void
    {
        $imports = new Imports();
        $this->assertSame([], $imports->getPagePaths());

        $paths = [
            'invalid bool' => true,
            'invalid int' => 7,
            'page_name' => '/absolute/path/to/page.yml',
        ];

        $expectedPaths = [
            'page_name' => '/absolute/path/to/page.yml',
        ];

        $imports = $imports->withPagePaths($paths);
        $this->assertSame($expectedPaths, $imports->getPagePaths());
    }

    public static function testGetWithDataProviderPaths(): void
    {
        $imports = new Imports();
        self::assertSame([], $imports->getDataProviderPaths());

        $paths = [
            'invalid bool' => true,
            'invalid int' => 7,
            'dataProvider_name' => '/absolute/path/to/dataProvider.yml',
        ];

        $expectedPaths = [
            'dataProvider_name' => '/absolute/path/to/dataProvider.yml',
        ];

        $imports = $imports->withDataProviderPaths($paths);
        self::assertSame($expectedPaths, $imports->getDataProviderPaths());
    }
}
