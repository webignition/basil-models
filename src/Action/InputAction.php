<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

class InputAction extends InteractionAction implements InputActionInterface
{
    public const TYPE = 'set';
    private const KEY_VALUE = 'value';

    private string $value;

    public function __construct(string $source, string $arguments, string $identifier, string $value)
    {
        parent::__construct($source, self::TYPE, $arguments, $identifier);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function withValue(string $value): InputActionInterface
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_VALUE => $this->value,
        ]);
    }

    public static function fromArray(array $data): InputActionInterface
    {
        $action = InteractionAction::fromArray($data);

        return new InputAction(
            $action->getSource(),
            $action->getArguments(),
            $action->getIdentifier(),
            (string) ($data[self::KEY_VALUE] ?? '')
        );
    }
}
