<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\EncapsulatingStatementData;

class ResolvedAction implements EncapsulatingActionInterface, \Stringable
{
    private ActionInterface $action;
    private EncapsulatingStatementData $encapsulatingStatementData;

    public function __construct(
        private readonly ActionInterface $sourceAction,
        ?string $identifier = null,
        ?string $value = null
    ) {
        $this->action = $this->createAction($sourceAction, $identifier, $value);
        $this->encapsulatingStatementData = $this->createEncapsulatingStatementData($sourceAction);
    }

    public function __toString(): string
    {
        return (string) $this->action;
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

    public function getSourceStatement(): ActionInterface
    {
        return $this->sourceAction;
    }

    public function getSource(): string
    {
        return $this->action->getSource();
    }

    public function isBrowserOperation(): bool
    {
        return $this->action->isBrowserOperation();
    }

    public function isInteraction(): bool
    {
        return $this->action->isInteraction();
    }

    public function isInput(): bool
    {
        return $this->action->isInput();
    }

    public function isWait(): bool
    {
        return $this->action->isWait();
    }

    public function jsonSerialize(): array
    {
        return $this->encapsulatingStatementData->jsonSerialize();
    }

    private function createAction(
        ActionInterface $sourceAction,
        ?string $identifier,
        ?string $value
    ): ActionInterface {
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

        return new Action(
            $source,
            $sourceAction->getType(),
            $sourceAction->getArguments(),
            $identifier,
            $value
        );
    }

    private function createEncapsulatingStatementData(ActionInterface $sourceAction): EncapsulatingStatementData
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
