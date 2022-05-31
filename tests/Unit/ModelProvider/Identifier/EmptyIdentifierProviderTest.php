<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\ModelProvider\Identifier;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\Identifier\EmptyIdentifierProvider;

class EmptyIdentifierProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testFindThrowsUnknownItemException(): void
    {
        $this->expectException(UnknownItemException::class);
        $this->expectExceptionMessage('Unknown identifier "name"');

        $provider = new EmptyIdentifierProvider();
        $provider->find('name');
    }
}
