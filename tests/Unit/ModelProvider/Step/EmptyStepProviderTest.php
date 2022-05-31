<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\ModelProvider\Step;

use webignition\BasilModels\ModelProvider\Exception\UnknownItemException;
use webignition\BasilModels\ModelProvider\Step\EmptyStepProvider;

class EmptyStepProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testFindThrowsUnknownItemException(): void
    {
        $this->expectException(UnknownItemException::class);
        $this->expectExceptionMessage('Unknown step "step_import_name"');

        $provider = new EmptyStepProvider();
        $provider->find('step_import_name');
    }
}
