<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Model\Statement\Action;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Statement\Action\Action;
use webignition\BasilModels\Model\Statement\Action\ActionCollection;
use webignition\BasilModels\Model\Statement\Action\ActionCollectionInterface;

class ActionCollectionTest extends TestCase
{
    #[DataProvider('countDataProvider')]
    public function testCount(ActionCollectionInterface $collection, int $expected): void
    {
        self::assertCount($expected, $collection);
    }

    /**
     * @return array<mixed>
     */
    public static function countDataProvider(): array
    {
        return [
            'empty' => [
                'collection' => new ActionCollection([]),
                'expected' => 0,
            ],
            'one' => [
                'collection' => new ActionCollection([
                    new Action('back', 0, 'back'),
                ]),
                'expected' => 1,
            ],
            'three' => [
                'collection' => new ActionCollection([
                    new Action('back', 0, 'back'),
                    new Action('reload', 0, 'reload'),
                    new Action('forward', 0, 'forward'),
                ]),
                'expected' => 3,
            ],
        ];
    }
}
