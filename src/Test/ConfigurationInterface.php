<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

interface ConfigurationInterface
{
    public function getBrowser(): string;
    public function getUrl(): string;
}
