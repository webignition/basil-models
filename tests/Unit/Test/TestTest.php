<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Test;

use webignition\BasilModels\Action\WaitAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Step\Step;
use webignition\BasilModels\Step\StepInterface;
use webignition\BasilModels\Test\Configuration;
use webignition\BasilModels\Test\ConfigurationInterface;
use webignition\BasilModels\Test\Test;

class TestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $path
     * @param ConfigurationInterface $configuration
     * @param array<mixed> $steps
     * @param StepInterface[] $expectedSteps
     */
    public function testCreate(
        string $path,
        ConfigurationInterface $configuration,
        array $steps,
        array $expectedSteps
    ) {
        $test = new Test($path, $configuration, $steps);

        $this->assertSame($path, $test->getPath());
        $this->assertSame($configuration, $test->getConfiguration());
        $this->assertEquals($expectedSteps, $test->getSteps());
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'path' => 'test.yml',
                'configuration' => new Configuration('', ''),
                'steps' => [],
                'expectedSteps' => [],
            ],
            'valid and invalid steps' => [
                'path' => 'test.yml',
                'configuration' => new Configuration('', ''),
                'steps' => [
                    1,
                    'string',
                    true,
                    new Step(
                        [
                            new WaitAction('wait 1', '1'),
                        ],
                        [
                            new Assertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                    'step name' => new Step(
                        [
                            new WaitAction('wait 1', '1'),
                        ],
                        [
                            new Assertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                ],
                'expectedSteps' => [
                    '3' => new Step(
                        [
                            new WaitAction('wait 1', '1'),
                        ],
                        [
                            new Assertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                    'step name' => new Step(
                        [
                            new WaitAction('wait 1', '1'),
                        ],
                        [
                            new Assertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                ],
            ],
        ];
    }
}
