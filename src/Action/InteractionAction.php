<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

class InteractionAction extends Action implements InteractionActionInterface
{
    private $identifier;

    public function __construct(string $source, string $type, string $arguments, string $identifier)
    {
        parent::__construct($source, $type, $arguments);

        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
