<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface InteractionActionInterface extends ActionInterface
{
    public function getIdentifier(): string;
    public function withIdentifier(string $identifier): InteractionActionInterface;

    /**
     * @param array<mixed> $data
     *
     * @return InteractionActionInterface
     */
    public static function fromArray(array $data): InteractionActionInterface;
}
