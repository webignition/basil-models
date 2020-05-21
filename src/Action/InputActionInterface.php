<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

interface InputActionInterface extends InteractionActionInterface
{
    public function getValue(): string;
    public function withValue(string $value): InputActionInterface;

    /**
     * @param array<mixed> $data
     *
     * @return InputActionInterface
     */
    public static function fromArray(array $data): InputActionInterface;
}
