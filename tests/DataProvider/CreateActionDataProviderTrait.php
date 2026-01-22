<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\DataProvider;

use webignition\BasilModels\Model\Statement\Action\Action;
use webignition\BasilModels\Model\Statement\Action\ResolvedAction;

trait CreateActionDataProviderTrait
{
    /**
     * @return array<mixed>
     */
    public static function createActionDataProvider(): array
    {
        return [
            'back, index=0' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'back',
                    'index' => 0,
                    'type' => 'back',
                    'arguments' => '',
                ]),
                'expected' => new Action('back', 0, 'back', ''),
            ],
            'back, index=2' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'back',
                    'index' => 2,
                    'type' => 'back',
                    'arguments' => '',
                ]),
                'expected' => new Action('back', 2, 'back', ''),
            ],
            'click' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'click $".selector"',
                    'index' => 0,
                    'type' => 'click',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ]),
                'expected' => new Action(
                    'click $".selector"',
                    0,
                    'click',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'submit' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'submit $".selector"',
                    'index' => 0,
                    'type' => 'submit',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ]),
                'expected' => new Action(
                    'submit $".selector"',
                    0,
                    'submit',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'wait-for' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'wait-for $".selector"',
                    'index' => 0,
                    'type' => 'wait-for',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                ]),
                'expected' => new Action(
                    'wait-for $".selector"',
                    0,
                    'wait-for',
                    '$".selector"',
                    '$".selector"'
                ),
            ],
            'set' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'set $".selector" to "value"',
                    'index' => 0,
                    'type' => 'set',
                    'arguments' => '$".selector"',
                    'identifier' => '$".selector"',
                    'value' => '"value"',
                ]),
                'expected' => new Action(
                    'set $".selector" to "value"',
                    0,
                    'set',
                    '$".selector"',
                    '$".selector"',
                    '"value"'
                ),
            ],
            'wait' => [
                'json' => json_encode([
                    'statement-type' => 'action',
                    'source' => 'wait 30',
                    'index' => 0,
                    'type' => 'wait',
                    'arguments' => '30',
                    'value' => '30',
                ]),
                'expected' => new Action(
                    'wait 30',
                    0,
                    'wait',
                    '30',
                    null,
                    '30'
                ),
            ],
            'resolved browser operation (back)' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'resolved-action',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'index' => 0,
                        'source' => 'back',
                        'type' => 'back',
                    ],
                ]),
                'expected' => new ResolvedAction(
                    new Action('back', 0, 'back')
                ),
            ],
            'resolved interaction (click)' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'resolved-action',
                        'identifier' => '$".selector"',
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'index' => 0,
                        'source' => 'click $page_import_name.elements.element_name',
                        'type' => 'click',
                        'arguments' => '$page_import_name.elements.element_name',
                        'identifier' => '$page_import_name.elements.element_name',
                    ],
                ]),
                'expected' => new ResolvedAction(
                    new Action(
                        'click $page_import_name.elements.element_name',
                        0,
                        'click',
                        '$page_import_name.elements.element_name',
                        '$page_import_name.elements.element_name'
                    ),
                    '$".selector"'
                ),
            ],
            'resolved input (set)' => [
                'json' => json_encode([
                    'container' => [
                        'type' => 'resolved-action',
                        'identifier' => '$".selector"',
                        'value' => '"value"'
                    ],
                    'statement' => [
                        'statement-type' => 'action',
                        'index' => 0,
                        'source' => 'set $page_import_name.elements.element_name to "value"',
                        'type' => 'set',
                        'arguments' => '$page_import_name.elements.element_name to "value"',
                        'identifier' => '$page_import_name.elements.element_name',
                        'value' => '"value"',
                    ],
                ]),
                'expected' => new ResolvedAction(
                    new Action(
                        'set $page_import_name.elements.element_name to "value"',
                        0,
                        'set',
                        '$page_import_name.elements.element_name to "value"',
                        '$page_import_name.elements.element_name',
                        '"value"'
                    ),
                    '$".selector"',
                    '"value"'
                ),
            ],
        ];
    }
}
