<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface WaitActionInterface extends ActionInterface
{
    public function getDuration(): string;
}
