<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

class FooResolvedAction implements FooResolvedActionInterface
{
    private FooActionInterface $sourceAction;
    private FooActionInterface $action;

    public function __construct(
        FooActionInterface $sourceAction,
        ?string $identifier = null,
        ?string $value = null
    ) {
        $this->sourceAction = $sourceAction;

        $type = $sourceAction->getType();

        $source = $type;

        if (null !== $identifier) {
            $source .= ' ' . $identifier;
        }

        if ('set' === $type) {
            $source .= ' to';
        }

        if (null !== $value) {
            $source .= ' ' . $value;
        }

        $this->action = new FooAction(
            $source,
            $sourceAction->getType(),
            $sourceAction->getArguments(),
            $identifier,
            $value
        );
    }

    public function getType(): string
    {
        return $this->action->getType();
    }

    public function getArguments(): ?string
    {
        return $this->action->getArguments();
    }

    public function getIdentifier(): ?string
    {
        return $this->action->getIdentifier();
    }

    public function getValue(): ?string
    {
        return $this->action->getValue();
    }

    public function getSourceAction(): FooActionInterface
    {
        return $this->sourceAction;
    }

    public function getSource(): string
    {
        return $this->action->getSource();
    }

    public function __toString(): string
    {
        return (string) $this->action;
    }

    public function jsonSerialize(): array
    {
        $encapsulationData = [
            'container' => 'resolved-action',
        ];

        $identifier = $this->action->getIdentifier();
        if (null !== $identifier) {
            $encapsulationData['identifier'] = $identifier;
        }

        $value = $this->action->getValue();
        if (null !== $value) {
            $encapsulationData['value'] = $value;
        }

        return [
            'encapsulation' => $encapsulationData,
            'encapsulates' => $this->sourceAction->jsonSerialize(),
        ];
    }
}
