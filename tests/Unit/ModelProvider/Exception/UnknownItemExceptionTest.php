<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\ModelProvider\Exception;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;

class UnknownItemExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate(): void
    {
        $exception = new UnknownItemException(UnknownItemException::TYPE_DATASET, 'item_name');

        $this->assertSame(UnknownItemException::TYPE_DATASET, $exception->getType());
        $this->assertSame('item_name', $exception->getName());
    }
}
