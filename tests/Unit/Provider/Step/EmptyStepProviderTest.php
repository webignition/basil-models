<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Provider\Step;

use webignition\BasilModels\Provider\Exception\UnknownItemException;
use webignition\BasilModels\Provider\Step\EmptyStepProvider;

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
