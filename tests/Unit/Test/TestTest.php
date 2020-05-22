<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Test;

use webignition\BasilModels\Action\FooAction;
use webignition\BasilModels\Assertion\FooAssertion;
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
     * @param ConfigurationInterface $configuration
     * @param array<mixed> $steps
     * @param StepInterface[] $expectedSteps
     */
    public function testCreate(
        ConfigurationInterface $configuration,
        array $steps,
        array $expectedSteps
    ) {
        $test = new Test($configuration, $steps);

        $this->assertSame($configuration, $test->getConfiguration());
        $this->assertEquals($expectedSteps, $test->getSteps());
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'configuration' => new Configuration('', ''),
                'steps' => [],
                'expectedSteps' => [],
            ],
            'valid and invalid steps' => [
                'configuration' => new Configuration('', ''),
                'steps' => [
                    1,
                    'string',
                    true,
                    new Step(
                        [
                            new FooAction('wait 1', 'wait', '1', null, '1'),
                        ],
                        [
                            new FooAssertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                    'step name' => new Step(
                        [
                            new FooAction('wait 1', 'wait', '1', null, '1'),
                        ],
                        [
                            new FooAssertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                ],
                'expectedSteps' => [
                    '3' => new Step(
                        [
                            new FooAction('wait 1', 'wait', '1', null, '1'),
                        ],
                        [
                            new FooAssertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                    'step name' => new Step(
                        [
                            new FooAction('wait 1', 'wait', '1', null, '1'),
                        ],
                        [
                            new FooAssertion('".selector" exists', '".selector"', 'exists'),
                        ]
                    ),
                ],
            ],
        ];
    }

    public function testWithPath()
    {
        $path = 'test.yml';

        $test = new Test(new Configuration('', ''), []);
        $this->assertNull($test->getPath());

        $mutatedTest = $test->withPath($path);
        $this->assertSame($path, $mutatedTest->getPath());
        $this->assertNotSame($test, $mutatedTest);
    }
}
