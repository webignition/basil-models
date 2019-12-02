<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface InteractionActionInterface extends ActionInterface
{
    public function getIdentifier(): string;
    public function withIdentifier(string $identifier): InteractionActionInterface;
}
