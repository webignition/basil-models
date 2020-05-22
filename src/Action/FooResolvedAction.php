<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\EncapsulatingStatementData;

class FooResolvedAction implements FooResolvedActionInterface
{
    private FooActionInterface $sourceAction;
    private FooActionInterface $action;
    private EncapsulatingStatementData $encapsulatingStatementData;

    public function __construct(
        FooActionInterface $sourceAction,
        ?string $identifier = null,
        ?string $value = null
    ) {
        $this->sourceAction = $sourceAction;
        $this->action = $this->createAction($sourceAction, $identifier, $value);
        $this->encapsulatingStatementData = $this->createEncapsulatingStatementData($sourceAction);
    }

    public function getStatementType(): string
    {
        return 'action';
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
        return $this->encapsulatingStatementData->jsonSerialize();
    }

    private function createAction(
        FooActionInterface $sourceAction,
        ?string $identifier,
        ?string $value
    ): FooActionInterface {
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

        return new FooAction(
            $source,
            $sourceAction->getType(),
            $sourceAction->getArguments(),
            $identifier,
            $value
        );
    }

    private function createEncapsulatingStatementData(FooActionInterface $sourceAction): EncapsulatingStatementData
    {
        $encapsulatingData = [];

        $identifier = $this->action->getIdentifier();
        if (null !== $identifier) {
            $encapsulatingData['identifier'] = $identifier;
        }

        $value = $this->action->getValue();
        if (null !== $value) {
            $encapsulatingData['value'] = $value;
        }

        return new EncapsulatingStatementData(
            $sourceAction,
            'resolved-action',
            $encapsulatingData
        );
    }
}
