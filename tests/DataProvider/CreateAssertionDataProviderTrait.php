<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\DataProvider;

use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\Assertion\ResolvedAssertion;

trait CreateAssertionDataProviderTrait
{
    /**
     * @return array<mixed>
     */
    public static function createAssertionDataProvider(): array
    {
        return [
            'exists, index=0' => [
                'json' => json_encode([
                    'statement-type' => 'assertion',
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                    'index' => 0,
                ]),
                'expected' => new Assertion('$".selector" exists', 0, '$".selector"', 'exists'),
            ],
            'exists, index=6' => [
                'json' => json_encode([
                    'statement-type' => 'assertion',
                    'source' => '$".selector" exists',
                    'identifier' => '$".selector"',
                    'operator' => 'exists',
                    'index' => 6,
                ]),
                'expected' => new Assertion('$".selector" exists', 6, '$".selector"', 'exists'),
            ],
            'is' => [
                'json' => json_encode([
                    'statement-type' => 'assertion',
                    'source' => '$".selector" is "value"',
                    'identifier' => '$".selector"',
                    'operator' => 'is',
                    'value' => '"value"',
                    'index' => 0,
                ]),
                'expected' => new Assertion(
                    '$".selector" is "value"',
                    0,
                    '$".selector"',
                    'is',
                    '"value"'
                ),
            ],
            'derived exists from action' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'source' => 'click $".selector"',
                        'type' => 'click',
                        'arguments' => '$".selector"',
                        'identifier' => '$".selector"',
                        'index' => 0,
                    ],
                ]),
                'expected' => new DerivedValueOperationAssertion(
                    new Action(
                        'click $".selector"',
                        0,
                        'click',
                        '$".selector"',
                        '$".selector"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
            'derived exists from assertion' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$".selector" is "value',
                        'identifier' => '$".selector"',
                        'operator' => 'is',
                        'value' => '"value"',
                        'index' => 0,
                    ],
                ]),
                'expected' => new DerivedValueOperationAssertion(
                    new Assertion(
                        '$".selector" is "value',
                        0,
                        '$".selector"',
                        'is',
                        '"value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
            'resolved exists assertion' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'resolved-assertion',
                        'source' => '$".selector" exists',
                        'identifier' => '$".selector"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.element_name exists',
                        'identifier' => '$page_import_name.elements.element_name',
                        'operator' => 'exists',
                        'index' => 0,
                    ],
                ]),
                'expected' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.element_name exists',
                        0,
                        '$page_import_name.elements.element_name',
                        'exists'
                    ),
                    '$".selector"'
                ),
            ],
            'resolved is assertion' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'resolved-assertion',
                        'source' => '$".selector" is $".value"',
                        'identifier' => '$".selector"',
                        'value' => '$".value"',
                    ],
                    'statement' => [
                        'statement-type' => 'assertion',
                        'source' => '$page_import_name.elements.selector is $page_import_name.elements.value',
                        'identifier' => '$page_import_name.elements.selector',
                        'operator' => 'is',
                        'value' => '$page_import_name.elements.value',
                        'index' => 0,
                    ],
                ]),
                'expected' => new ResolvedAssertion(
                    new Assertion(
                        '$page_import_name.elements.selector is $page_import_name.elements.value',
                        0,
                        '$page_import_name.elements.selector',
                        'is',
                        '$page_import_name.elements.value'
                    ),
                    '$".selector"',
                    '$".value"'
                ),
            ],
            'derived exists from resolved assertion' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'container' => [
                            'type' => 'resolved-assertion',
                            'source' => '$".selector" is $".value"',
                            'identifier' => '$".selector"',
                            'value' => '$".value"',
                        ],
                        'statement' => [
                            'statement-type' => 'assertion',
                            'source' => '$page_import_name.elements.selector is $page_import_name.elements.value',
                            'identifier' => '$page_import_name.elements.selector',
                            'operator' => 'is',
                            'value' => '$page_import_name.elements.value',
                            'index' => 0,
                        ],
                    ],
                ]),
                'expected' => new DerivedValueOperationAssertion(
                    new ResolvedAssertion(
                        new Assertion(
                            '$page_import_name.elements.selector is $page_import_name.elements.value',
                            0,
                            '$page_import_name.elements.selector',
                            'is',
                            '$page_import_name.elements.value'
                        ),
                        '$".selector"',
                        '$".value"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
            'derived exists from resolved action' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'derived-value-operation-assertion',
                        'value' => '$".selector"',
                        'operator' => 'exists',
                    ],
                    'statement' => [
                        'container' => [
                            'type' => 'resolved-action',
                            'identifier' => '$".selector"',
                        ],
                        'statement' => [
                            'statement-type' => 'action',
                            'source' => 'click $page_import_name.elements.selector',
                            'type' => 'click',
                            'arguments' => '$page_import_name.elements.selector',
                            'identifier' => '$page_import_name.elements.selector',
                            'index' => 0,
                        ],
                    ],
                ]),
                'expected' => new DerivedValueOperationAssertion(
                    new ResolvedAction(
                        new Action(
                            'click $page_import_name.elements.selector',
                            0,
                            'click',
                            '$page_import_name.elements.selector',
                            '$page_import_name.elements.selector'
                        ),
                        '$".selector"'
                    ),
                    '$".selector"',
                    'exists'
                ),
            ],
        ];
    }
}
