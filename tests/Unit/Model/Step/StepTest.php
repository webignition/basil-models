<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Step;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ActionCollection;
use webignition\BasilModels\Model\Action\ActionCollectionInterface;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionCollection;
use webignition\BasilModels\Model\Assertion\AssertionCollectionInterface;
use webignition\BasilModels\Model\DataSet\DataSetCollection;
use webignition\BasilModels\Model\Step\Step;
use webignition\BasilModels\Model\Step\StepInterface;

class StepTest extends TestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(
        ActionCollectionInterface $actions,
        AssertionCollectionInterface $assertions,
        ActionCollectionInterface $expectedActions,
        AssertionCollectionInterface $expectedAssertions
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
                'actions' => new ActionCollection([]),
                'assertions' => new AssertionCollection([]),
                'expectedActions' => new ActionCollection([]),
                'expectedAssertions' => new AssertionCollection([]),
            ],
            'all valid' => [
                'actions' => new ActionCollection([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                    new Action('click $".selector"', 0, 'click', '$".selector"', '$".selector"'),
                ]),
                'assertions' => new AssertionCollection([
                    new Assertion('$page.title is "Example"', 0, '$page.title', 'is', '"Example"'),
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ]),
                'expectedActions' => new ActionCollection([
                    new Action('wait 1', 0, 'wait', '1', null, '1'),
                    new Action('click $".selector"', 0, 'click', '$".selector"', '$".selector"'),
                ]),
                'expectedAssertions' => new AssertionCollection([
                    new Assertion('$page.title is "Example"', 0, '$page.title', 'is', '"Example"'),
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ]),
            ],
        ];
    }

    public function testGetDataWithData(): void
    {
        $step = new Step(new ActionCollection([]), new AssertionCollection([]));
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
        $step = new Step(new ActionCollection([]), new AssertionCollection([]));
        $this->assertNull($step->getImportName());

        $step = $step->withImportName('import_name');
        $this->assertSame('import_name', $step->getImportName());

        $step = $step->removeImportName();
        $this->assertNull($step->getImportName());
    }

    public function testDataImportName(): void
    {
        $step = new Step(new ActionCollection([]), new AssertionCollection([]));
        $this->assertNull($step->getDataImportName());

        $step = $step->withDataImportName('data_import_name');
        $this->assertSame('data_import_name', $step->getDataImportName());

        $step = $step->removeDataImportName();
        $this->assertNull($step->getDataImportName());
    }

    public function testElements(): void
    {
        $step = new Step(new ActionCollection([]), new AssertionCollection([]));
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
                'step' => new Step(new ActionCollection([]), new AssertionCollection([])),
                'expectedRequiresImportResolution' => false,
            ],
            'no import name, has data provider import name' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([]))
                    ->withDataImportName('data_import_name'),
                'expectedRequiresImportResolution' => true,
            ],
            'has import name, no data provider import name' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([]))
                    ->withImportName('import_name'),
                'expectedRequiresImportResolution' => true,
            ],
            'has import name, has data provider import name' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([]))
                    ->withImportName('import_name')
                    ->withDataImportName('data_import_name'),
                'expectedRequiresImportResolution' => true,
            ],
        ];
    }

    public function testWithActions(): void
    {
        $step = new Step(new ActionCollection([]), new AssertionCollection([]));
        $this->assertEquals(new ActionCollection([]), $step->getActions());

        $actions = new ActionCollection([
            new Action('click $".selector"', 0, 'click', '$".selector"'),
        ]);

        $mutatedStep = $step->withActions($actions);

        $this->assertNotSame($step, $mutatedStep);
        $this->assertEquals(new ActionCollection([]), $step->getActions());
        $this->assertEquals($actions, $mutatedStep->getActions());
    }

    public function testWithAssertions(): void
    {
        $step = new Step(new ActionCollection([]), new AssertionCollection([]));
        $this->assertEquals(new AssertionCollection([]), $step->getAssertions());

        $assertions = new AssertionCollection([
            new Assertion('$".selector exists', 0, '$".selector"', 'exists'),
        ]);

        $mutatedStep = $step->withAssertions($assertions);

        $this->assertNotSame($step, $mutatedStep);
        $this->assertEquals(new AssertionCollection([]), $step->getAssertions());
        $this->assertEquals($assertions, $mutatedStep->getAssertions());
    }

    #[DataProvider('withPrependedActionsDataProvider')]
    public function testWithPrependedActions(
        StepInterface $step,
        ActionCollectionInterface $actions,
        StepInterface $expectedStep
    ): void {
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
                'step' => new Step(new ActionCollection([]), new AssertionCollection([])),
                'actions' => new ActionCollection([]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([])),
            ],
            'has actions, empty prepended actions' => [
                'step' => new Step(
                    new ActionCollection([
                        new Action('wait 1', 0, 'wait', '1', null, '1'),
                    ]),
                    new AssertionCollection([]),
                ),
                'actions' => new ActionCollection([]),
                'expectedStep' => new Step(
                    new ActionCollection([
                        new Action('wait 1', 0, 'wait', '1', null, '1'),
                    ]),
                    new AssertionCollection([]),
                ),
            ],
            'has no actions, non-empty prepended actions' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([])),
                'actions' => new ActionCollection([
                    new Action('wait 2', 0, 'wait', '2', null, '2'),
                ]),
                'expectedStep' => new Step(
                    new ActionCollection([
                        new Action('wait 2', 0, 'wait', '2', null, '2'),
                    ]),
                    new AssertionCollection([]),
                ),
            ],
            'has actions, non-empty prepended actions' => [
                'step' => new Step(
                    new ActionCollection([
                        new Action('wait 1', 0, 'wait', '1', null, '1'),
                    ]),
                    new AssertionCollection([]),
                ),
                'actions' => new ActionCollection([
                    new Action('wait 2', 0, 'wait', '2', null, '2'),
                ]),
                'expectedStep' => new Step(
                    new ActionCollection([
                        new Action('wait 2', 0, 'wait', '2', null, '2'),
                        new Action('wait 1', 0, 'wait', '1', null, '1'),
                    ]),
                    new AssertionCollection([]),
                ),
            ],
            'assertions are retained' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ])),
                'actions' => new ActionCollection([]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([
                    new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
                ])),
            ],
            'data sets are retained' => [
                'step' => new Step(
                    new ActionCollection([]),
                    new AssertionCollection([])
                )->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
                'actions' => new ActionCollection([]),
                'expectedStep' => new Step(
                    new ActionCollection([]),
                    new AssertionCollection([])
                )->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
            ],
            'identifier collection is retained' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([]))->withIdentifiers([
                    'heading' => '$".heading"'
                ]),
                'actions' => new ActionCollection([]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([]))->withIdentifiers([
                    'heading' => '$".heading"'
                ]),
            ],
        ];
    }

    #[DataProvider('withPrependedAssertionsDataProvider')]
    public function testWithPrependedAssertions(
        StepInterface $step,
        AssertionCollectionInterface $assertions,
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
                'step' => new Step(new ActionCollection([]), new AssertionCollection([])),
                'assertions' => new AssertionCollection([]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([])),
            ],
            'has assertions, empty prepended assertions' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([
                    $assertion1,
                ])),
                'assertions' => new AssertionCollection([]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([
                    $assertion1,
                ])),
            ],
            'has no assertions, non-empty prepended assertions' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([])),
                'assertions' => new AssertionCollection([
                    $assertion1,
                ]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([
                    $assertion1,
                ])),
            ],
            'has assertions, non-empty prepended assertions' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([
                    $assertion1,
                ])),
                'assertions' => new AssertionCollection([
                    $assertion2,
                ]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([
                    $assertion2,
                    $assertion1,
                ])),
            ],
            'actions are retained' => [
                'step' => new Step(
                    new ActionCollection([
                        new Action('wait 1', 0, 'wait', '1', null, '1'),
                    ]),
                    new AssertionCollection([]),
                ),
                'assertions' => new AssertionCollection([]),
                'expectedStep' => new Step(
                    new ActionCollection([
                        new Action('wait 1', 0, 'wait', '1', null, '1'),
                    ]),
                    new AssertionCollection([]),
                ),
            ],
            'data sets are retained' => [
                'step' => new Step(
                    new ActionCollection([]),
                    new AssertionCollection([])
                )->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
                'assertions' => new AssertionCollection([]),
                'expectedStep' => new Step(
                    new ActionCollection([]),
                    new AssertionCollection([])
                )->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ],
                ])),
            ],
            'identifier collection is retained' => [
                'step' => new Step(new ActionCollection([]), new AssertionCollection([]))->withIdentifiers([
                    'heading' => '.heading'
                ]),
                'assertions' => new AssertionCollection([]),
                'expectedStep' => new Step(new ActionCollection([]), new AssertionCollection([]))->withIdentifiers([
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
                'step' => new Step(new ActionCollection([]), new AssertionCollection([])),
                'expectedDataParameterNames' => [],
            ],
            'has actions, has assertions, no data parameters' => [
                'step' => new Step(
                    new ActionCollection([
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
                    ]),
                    new AssertionCollection([
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
                    ]),
                ),
                'expectedDataParameterNames' => [],
            ],
            'has actions, has assertions, has data parameters' => [
                'step' => new Step(
                    new ActionCollection([
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
                    ]),
                    new AssertionCollection([
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
                    ]),
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
