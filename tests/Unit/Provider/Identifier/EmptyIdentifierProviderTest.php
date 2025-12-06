<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Provider\Identifier;

use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\Identifier\EmptyIdentifierProvider;

class EmptyIdentifierProviderTest extends TestCase
{
    public function testFindThrowsUnknownItemException(): void
    {
        $this->expectException(UnknownItemException::class);
        $this->expectExceptionMessage('Unknown identifier "name"');

        $provider = new EmptyIdentifierProvider();
        $provider->find('name');
    }
}
