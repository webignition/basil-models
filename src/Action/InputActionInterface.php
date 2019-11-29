<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface InputActionInterface extends InteractionActionInterface
{
    public function getValue(): string;
}
