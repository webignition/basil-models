<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Step;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Action\WaitAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\DataSet\DataSetCollection;
use webignition\BasilModels\Step\Step;
use webignition\BasilModels\Step\StepInterface;

class StepTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        array $actions,
        array $assertions,
        array $expectedActions,
        array $expectedAssertions
    ) {
        $step = new Step($actions, $assertions);

        $this->assertEquals($expectedActions, $step->getActions());
        $this->assertEquals($expectedAssertions, $step->getAssertions());
    }

    public function createDataProvider(): array
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
                    new WaitAction('wait 1', '1'),
                    new InteractionAction('click ".selector"', 'click', '".selector"', '".selector"'),
                ],
                'assertions' => [
                    new ComparisonAssertion('$page.title is "Example"', '$page.title', 'is', '"Example"'),
                    new Assertion('".selector" exists', '".selector"', 'exists'),
                ],
                'expectedActions' => [
                    new WaitAction('wait 1', '1'),
                    new InteractionAction('click ".selector"', 'click', '".selector"', '".selector"'),
                ],
                'expectedAssertions' => [
                    new ComparisonAssertion('$page.title is "Example"', '$page.title', 'is', '"Example"'),
                    new Assertion('".selector" exists', '".selector"', 'exists'),
                ],
            ],
        ];
    }

    public function testGetDataWithData()
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

    public function testImportName()
    {
        $step = new Step([], []);
        $this->assertNull($step->getImportName());

        $step = $step->withImportName('import_name');
        $this->assertSame('import_name', $step->getImportName());

        $step = $step->removeImportName();
        $this->assertNull($step->getImportName());
    }

    public function testDataImportName()
    {
        $step = new Step([], []);
        $this->assertNull($step->getDataImportName());

        $step = $step->withDataImportName('data_import_name');
        $this->assertSame('data_import_name', $step->getDataImportName());

        $step = $step->removeDataImportName();
        $this->assertNull($step->getDataImportName());
    }

    public function testElements()
    {
        $step = new Step([], []);
        $this->assertSame([], $step->getIdentifiers());

        $identifiers = [
            'heading' => 'page_import_name.elements.heading',
        ];

        $step = $step->withIdentifiers($identifiers);
        $this->assertSame($identifiers, $step->getIdentifiers());
    }

    /**
     * @dataProvider requiresImportResolutionDataProvider
     */
    public function testRequiresImportResolution(StepInterface $step, bool $expectedRequiresImportResolution)
    {
        $this->assertSame($expectedRequiresImportResolution, $step->requiresImportResolution());
    }

    public function requiresImportResolutionDataProvider(): array
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

    public function testWithActions()
    {
        $step = new Step([], []);
        $this->assertEquals([], $step->getActions());

        $actions = [
            new Action('click $".selector"', 'click', '$".selector"'),
        ];

        $mutatedStep = $step->withActions($actions);

        $this->assertNotSame($step, $mutatedStep);
        $this->assertEquals([], $step->getActions());
        $this->assertEquals($actions, $mutatedStep->getActions());
    }

    public function testWithAssertions()
    {
        $step = new Step([], []);
        $this->assertEquals([], $step->getAssertions());

        $assertions = [
            new Assertion('$".selector exists', '$".selector"', 'exists'),
        ];

        $mutatedStep = $step->withAssertions($assertions);

        $this->assertNotSame($step, $mutatedStep);
        $this->assertEquals([], $step->getAssertions());
        $this->assertEquals($assertions, $mutatedStep->getAssertions());
    }

    /**
     * @dataProvider withPrependedActionsDataProvider
     */
    public function testWithPrependedActions(StepInterface $step, array $actions, StepInterface $expectedStep)
    {
        $mutatedStep = $step->withPrependedActions($actions);

        $this->assertEquals($expectedStep, $mutatedStep);
    }

    public function withPrependedActionsDataProvider(): array
    {
        $assertion = new Assertion(
            '$".selector" exists',
            '$".selector"',
            'exists'
        );

        return [
            'has no actions, empty prepended actions' => [
                'step' => new Step([], []),
                'actions' => [],
                'expectedStep' => new Step([], []),
            ],
            'has actions, empty prepended actions' => [
                'step' => new Step([
                    new WaitAction('wait 1', '1'),
                ], []),
                'actions' => [],
                'expectedStep' => new Step([
                    new WaitAction('wait 1', '1'),
                ], []),
            ],
            'has no actions, non-empty prepended actions' => [
                'step' => new Step([], []),
                'actions' => [
                    new WaitAction('wait 2', '2'),
                ],
                'expectedStep' => new Step([
                    new WaitAction('wait 2', '2'),
                ], []),
            ],
            'has actions, non-empty prepended actions' => [
                'step' => new Step([
                    new WaitAction('wait 1', '1'),
                ], []),
                'actions' => [
                    new WaitAction('wait 2', '2'),
                ],
                'expectedStep' => new Step([
                    new WaitAction('wait 2', '2'),
                    new WaitAction('wait 1', '1'),
                ], []),
            ],
            'assertions are retained' => [
                'step' => new Step([], [
                    $assertion,
                ]),
                'actions' => [],
                'expectedStep' => new Step([], [
                    $assertion,
                ]),
            ],
            'data sets are retained' => [
                'step' => (new Step([], []))->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ]
                ])),
                'actions' => [],
                'expectedStep' => (new Step([], []))->withData(new DataSetCollection([
                    '0' => [
                        'field1' => 'value1',
                    ]
                ])),
            ],
            'identifier collection is retained' => [
                'step' => (new Step([], []))->withIdentifiers([
                    'heading1' => '$".heading"'
                ]),
                'actions' => [],
                'expectedStep' => (new Step([], []))->withIdentifiers([
                    'heading1' => '$".heading"'
                ]),
            ],
        ];
    }
}
