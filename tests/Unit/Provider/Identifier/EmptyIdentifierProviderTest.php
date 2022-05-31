<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Provider\Identifier;

use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\Identifier\EmptyIdentifierProvider;

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
