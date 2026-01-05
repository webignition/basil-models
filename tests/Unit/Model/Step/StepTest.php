<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Step;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\DataSet\DataSetCollection;
use webignition\BasilModels\Model\Step\Step;
use webignition\BasilModels\Model\Step\StepInterface;

class StepTest extends TestCase
{
    /**
     * @param array<mixed>         $actions
     * @param array<mixed>         $assertions
     * @param ActionInterface[]    $expectedActions
     * @param AssertionInterface[] $expectedAssertions
     */
    #[DataProvider('createDataProvider')]
    public function testCreate(
        array $actions,
        array $assertions,
        array $expectedActions,
        array $expectedAssertions
    ): void {
        $step = new Step($actions, $assertions);

        $this->assertEquals($expectedActions, $step->getActions());
        $this->assertEquals($expectedAssertions, $step->getAssertions());
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'empty' => [
                'actions' => [],
                'assertions' => [],
                'expectedActions' => [],
                'expectedAssertions' => [],
            ],
            'all invalid' => [
                'actions' => [
                    1,
                    true,
                    'string',
                ],
                'assertions' => [
                    1,
                    true,
                    'string',
                ],
                'expectedActions' => [],
                'expectedAssertions' => [],
            ],
            'all valid' => [
                'actions' => [
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                    new Action('click $".selector"', 0, 'click', '$".selector"', '$".selector"'),
                ],
                'assertions' => [
                    new Assertion('$page.title is "Example"', 0, '$page.title', 'is', '"Example"'),
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ],
                'expectedActions' => [
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                    new Action('click $".selector"', 0, 'click', '$".selector"', '$".selector"'),
                ],
                'expectedAssertions' => [
                    new Assertion('$page.title is "Example"', 0, '$page.title', 'is', '"Example"'),
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ],
            ],
        ];
    }

    public function testGetDataWithData(): void
    {
        $step = new Step([], []);
        $this->assertNull($step->getData());

        $data = new DataSetCollection([
            'set1' => [
                'key' => 'value',
            ],
        ]);

        $step = $step->withData($data);
        $this->assertSame($data, $step->getData());
    }

    public function testImportName(): void
    {
        $step = new Step([], []);
        $this->assertNull($step->getImportName());

        $step = $step->withImportName('import_name');
        $this->assertSame('import_name', $step->getImportName());

        $step = $step->removeImportName();
        $this->assertNull($step->getImportName());
    }

    public function testDataImportName(): void
    {
        $step = new Step([], []);
        $this->assertNull($step->getDataImportName());

        $step = $step->withDataImportName('data_import_name');
        $this->assertSame('data_import_name', $step->getDataImportName());

        $step = $step->removeDataImportName();
        $this->assertNull($step->getDataImportName());
    }

    public function testElements(): void
    {
        $step = new Step([], []);
        $this->assertSame([], $step->getIdentifiers());

        $identifiers = [
            'heading' => 'page_import_name.elements.heading',
        ];

        $step = $step->withIdentifiers($identifiers);
        $this->assertSame($identifiers, $step->getIdentifiers());
    }

    #[DataProvider('requiresImportResolutionDataProvider')]
    public function testRequiresImportResolution(StepInterface $step, bool $expectedRequiresImportResolution): void
    {
        $this->assertSame($expectedRequiresImportResolution, $step->requiresImportResolution());
    }

    /**
     * @return array<mixed>
     */
    public static function requiresImportResolutionDataProvider(): array
    {
        return [
            'no import name, no data provider import name' => [
                'step' => new Step([], []),
                'expectedRequiresImportResolution' => false,
            ],
            'no import name, has data provider import name' => [
                'step' => (new Step([], []))
                    ->withDataImportName('data_import_name'),
                'expectedRequiresImportResolution' => true,
            ],
            'has import name, no data provider import name' => [
                'step' => (new Step([], []))
                    ->withImportName('import_name'),
                'expectedRequiresImportResolution' => true,
            ],
            'has import name, has data provider import name' => [
                'step' => (new Step([], []))
                    ->withImportName('import_name')
                    ->withDataImportName('data_import_name'),
                'expectedRequiresImportResolution' => true,
            ],
        ];
    }

    public function testWithActions(): void
    {
        $step = new Step([], []);
        $this->assertEquals([], $step->getActions());

        $actions = [
            new Action('click $".selector"', 0, 'click', '$".selector"'),
        ];

        $mutatedStep = $step->withActions($actions);

        $this->assertNotSame($step, $mutatedStep);
        $this->assertEquals([], $step->getActions());
        $this->assertEquals($actions, $mutatedStep->getActions());
    }

    public function testWithAssertions(): void
    {
        $step = new Step([], []);
        $this->assertEquals([], $step->getAssertions());

        $assertions = [
            new Assertion('$".selector exists', 0, '$".selector"', 'exists'),
        ];

        $mutatedStep = $step->withAssertions($assertions);

        $this->assertNotSame($step, $mutatedStep);
        $this->assertEquals([], $step->getAssertions());
        $this->assertEquals($assertions, $mutatedStep->getAssertions());
    }

    /**
     * @param ActionInterface[] $actions
     */
    #[DataProvider('withPrependedActionsDataProvider')]
    public function testWithPrependedActions(StepInterface $step, array $actions, StepInterface $expectedStep): void
    {
        $mutatedStep = $step->withPrependedActions($actions);

        $this->assertEquals($expectedStep, $mutatedStep);
    }

    /**
     * @return array<mixed>
     */
    public static function withPrependedActionsDataProvider(): array
    {
        return [
            'has no actions, empty prepended actions' => [
                'step' => new Step([], []),
                'actions' => [],
                'expectedStep' => new Step([], []),
            ],
            'has actions, empty prepended actions' => [
                'step' => new Step([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                ], []),
                'actions' => [],
                'expectedStep' => new Step([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                ], []),
            ],
            'has no actions, non-empty prepended actions' => [
                'step' => new Step([], []),
                'actions' => [
                    new Action('wait 2', 0, 'wait', '2', null, '2'),
                ],
                'expectedStep' => new Step([
                    new Action('wait 2', 0, 'wait', '2', null, '2'),
                ], []),
            ],
            'has actions, non-empty prepended actions' => [
                'step' => new Step([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                ], []),
                'actions' => [
                    new Action('wait 2', 0, 'wait', '2', null, '2'),
                ],
                'expectedStep' => new Step([
                    new Action('wait 2', 0, 'wait', '2', null, '2'),
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                ], []),
            ],
            'assertions are retained' => [
                'step' => new Step([], [
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ]),
                'actions' => [],
                'expectedStep' => new Step([], [
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ]),
            ],
            'data sets are retained' => [
                'step' => (new Step([], []))->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
                'actions' => [],
                'expectedStep' => (new Step([], []))->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
            ],
            'identifier collection is retained' => [
                'step' => (new Step([], []))->withIdentifiers([
                    'heading' => '$".heading"'
                ]),
                'actions' => [],
                'expectedStep' => (new Step([], []))->withIdentifiers([
                    'heading' => '$".heading"'
                ]),
            ],
        ];
    }

    /**
     * @param AssertionInterface[] $assertions
     */
    #[DataProvider('withPrependedAssertionsDataProvider')]
    public function testWithPrependedAssertions(
        StepInterface $step,
        array $assertions,
        StepInterface $expectedStep
    ): void {
        $mutatedStep = $step->withPrependedAssertions($assertions);

        $this->assertEquals($expectedStep, $mutatedStep);
    }

    /**
     * @return array<mixed>
     */
    public static function withPrependedAssertionsDataProvider(): array
    {
        $assertion1 = new Assertion(
            '$".selector1" exists',
            0,
            '$".selector1"',
            'exists'
        );

        $assertion2 = new Assertion(
            '$".selector2" exists',
            0,
            '$".selector2"',
            'exists'
        );

        return [
            'has no assertions, empty prepended assertions' => [
                'step' => new Step([], []),
                'assertions' => [],
                'expectedStep' => new Step([], []),
            ],
            'has assertions, empty prepended assertions' => [
                'step' => new Step([], [
                    $assertion1,
                ]),
                'assertions' => [],
                'expectedStep' => new Step([], [
                    $assertion1,
                ]),
            ],
            'has no assertions, non-empty prepended assertions' => [
                'step' => new Step([], []),
                'assertions' => [
                    $assertion1,
                ],
                'expectedStep' => new Step([], [
                    $assertion1,
                ]),
            ],
            'has assertions, non-empty prepended assertions' => [
                'step' => new Step([], [
                    $assertion1,
                ]),
                'assertions' => [
                    $assertion2,
                ],
                'expectedStep' => new Step([], [
                    $assertion2,
                    $assertion1,
                ]),
            ],
            'actions are retained' => [
                'step' => new Step([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                ], []),
                'assertions' => [],
                'expectedStep' => new Step([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                ], []),
            ],
            'data sets are retained' => [
                'step' => (new Step([], []))->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
                'assertions' => [],
                'expectedStep' => (new Step([], []))->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
            ],
            'identifier collection is retained' => [
                'step' => (new Step([], []))->withIdentifiers([
                    'heading' => '.heading'
                ]),
                'assertions' => [],
                'expectedStep' => (new Step([], []))->withIdentifiers([
                    'heading' => '.heading'
                ]),
            ],
        ];
    }

    /**
     * @param string[] $expectedDataParameterNames
     */
    #[DataProvider('getDataParameterNamesDataProvider')]
    public function testGetDataParameterNames(StepInterface $step, array $expectedDataParameterNames): void
    {
        $this->assertSame($expectedDataParameterNames, $step->getDataParameterNames());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataParameterNamesDataProvider(): array
    {
        return [
            'empty' => [
                'step' => new Step([], []),
                'expectedDataParameterNames' => [],
            ],
            'has actions, has assertions, no data parameters' => [
                'step' => new Step(
                    [
                        new Action(
                            'click $".selector"',
                            0,
                            'click',
                            '$".selector"',
                            '$".selector"'
                        ),
                        new Action(
                            'set $".selector" to "value"',
                            1,
                            '$".selector" to "value"',
                            '$".selector"',
                            '"value"'
                        ),
                    ],
                    [
                        new Assertion(
                            '$".selector" exists',
                            2,
                            '$".selector"',
                            'exists'
                        ),
                        new Assertion(
                            '$".selector" is "value"',
                            3,
                            '$".selector"',
                            'is',
                            '"value"'
                        )
                    ]
                ),
                'expectedDataParameterNames' => [],
            ],
            'has actions, has assertions, has data parameters' => [
                'step' => new Step(
                    [
                        new Action(
                            'click $".selector"',
                            0,
                            'click',
                            '$".selector"',
                            '$".selector"'
                        ),
                        new Action(
                            'set $".selector" to $data.zebra',
                            1,
                            '$".selector" to $data.zebra',
                            '$".selector"',
                            '$data.zebra'
                        ),
                    ],
                    [
                        new Assertion(
                            '$data.aardvark exists',
                            2,
                            '$data.aardvark',
                            'exists'
                        ),
                        new Assertion(
                            '$data.cow is $data.bee',
                            3,
                            '$data.cow',
                            'is',
                            '$data.bee'
                        )
                    ]
                ),
                'expectedDataParameterNames' => [
                    'aardvark',
                    'bee',
                    'cow',
                    'zebra',
                ],
            ],
        ];
    }
}
