<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Step;

use webignition\BasilModels\Action\InteractionAction;
use webignition\BasilModels\Action\WaitAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\DataSet\DataSetCollection;
use webignition\BasilModels\Step\Step;

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
}
