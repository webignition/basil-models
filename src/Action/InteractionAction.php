<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

class InteractionAction extends Action implements InteractionActionInterface
{
    private const KEY_IDENTIFIER = 'identifier';

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

    public function withIdentifier(string $identifier): InteractionActionInterface
    {
        $new = clone $this;
        $new->identifier = $identifier;

        return $new;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_IDENTIFIER => $this->identifier,
        ]);
    }

    public static function fromArray(array $data): ?ActionInterface
    {
        $action = Action::fromArray($data);
        if (!$action instanceof ActionInterface) {
            return null;
        }

        $identifier = $data[self::KEY_IDENTIFIER] ?? null;
        if (null === $identifier) {
            return null;
        }

        return new InteractionAction(
            $action->getSource(),
            $action->getType(),
            $action->getArguments(),
            (string) $identifier
        );
    }

    public static function createsFromType(string $type): bool
    {
        return in_array($type, ['click', 'submit', 'wait-for']);
    }
}
