<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Step;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionCollectionInterface;
use webignition\BasilModels\Model\DataParameter\DataParameter;
use webignition\BasilModels\Model\DataSet\DataSetCollectionInterface;

class Step implements StepInterface
{
    /**
     * @var ActionInterface[]
     */
    private array $actions;

    private AssertionCollectionInterface $assertions;

    private ?DataSetCollectionInterface $data = null;
    private ?string $importName = null;
    private ?string $dataImportName = null;

    /**
     * @var array<string, string>
     */
    private array $identifiers;

    /**
     * @param array<mixed> $actions
     */
    public function __construct(array $actions, AssertionCollectionInterface $assertions)
    {
        $this->actions = [];
        $this->assertions = $assertions;
        $this->identifiers = [];

        $this->setActions($actions);
    }

    /**
     * @return ActionInterface[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param ActionInterface[] $actions
     */
    public function withActions(array $actions): StepInterface
    {
        $new = clone $this;
        $new->setActions($actions);

        return $new;
    }

    public function getAssertions(): AssertionCollectionInterface
    {
        return $this->assertions;
    }

    public function withAssertions(AssertionCollectionInterface $assertions): StepInterface
    {
        $new = clone $this;
        $new->assertions = $assertions;

        return $new;
    }

    public function getData(): ?DataSetCollectionInterface
    {
        return $this->data;
    }

    public function withData(DataSetCollectionInterface $data): StepInterface
    {
        $new = clone $this;
        $new->data = $data;

        return $new;
    }

    public function getImportName(): ?string
    {
        return $this->importName;
    }

    public function withImportName(string $importName): StepInterface
    {
        $new = clone $this;
        $new->importName = $importName;

        return $new;
    }

    public function removeImportName(): StepInterface
    {
        $new = clone $this;
        $new->importName = null;

        return $new;
    }

    public function getDataImportName(): ?string
    {
        return $this->dataImportName;
    }

    public function withDataImportName(string $dataImportName): StepInterface
    {
        $new = clone $this;
        $new->dataImportName = $dataImportName;

        return $new;
    }

    public function removeDataImportName(): StepInterface
    {
        $new = clone $this;
        $new->dataImportName = null;

        return $new;
    }

    /**
     * @return array<string, string>
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * @param array<string, string> $identifiers
     */
    public function withIdentifiers(array $identifiers): Step
    {
        $new = clone $this;
        $new->identifiers = $identifiers;

        return $new;
    }

    public function requiresImportResolution(): bool
    {
        return null !== $this->importName || null !== $this->dataImportName;
    }

    public function withPrependedActions(array $actions): StepInterface
    {
        $actions = $this->filterActions($actions);

        foreach ($this->getActions() as $action) {
            $actions[] = clone $action;
        }

        $new = clone $this;
        $new->actions = $actions;

        return $new;
    }

    public function withPrependedAssertions(AssertionCollectionInterface $assertions): StepInterface
    {
        $new = clone $this;
        $new->assertions = $this->assertions->prepend($assertions);

        return $new;
    }

    public function getDataParameterNames(): array
    {
        $dataParameterNames = [];

        foreach ($this->getActions() as $action) {
            $identifier = $action->getIdentifier();
            if (null !== $identifier) {
                $this->addDataParameterName($identifier, $dataParameterNames);
            }

            $value = $action->getValue();
            if (null !== $value) {
                $this->addDataParameterName($value, $dataParameterNames);
            }
        }

        foreach ($this->getAssertions() as $assertion) {
            $identifier = (string) $assertion->getIdentifier();
            $this->addDataParameterName($identifier, $dataParameterNames);

            $value = $assertion->getValue();
            if (null !== $value) {
                $this->addDataParameterName($value, $dataParameterNames);
            }
        }

        $dataParameterNames = array_unique($dataParameterNames);
        sort($dataParameterNames);

        return $dataParameterNames;
    }

    /**
     * @param array<mixed> $actions
     */
    private function setActions(array $actions): void
    {
        $this->actions = $this->filterActions($actions);
    }

    /**
     * @param array<mixed> $actions
     *
     * @return ActionInterface[]
     */
    private function filterActions(array $actions): array
    {
        return array_filter($actions, function ($action) {
            return $action instanceof ActionInterface;
        });
    }

    /**
     * @param string[] $dataParameterNames
     */
    private function addDataParameterName(string $value, array &$dataParameterNames): void
    {
        if (DataParameter::is($value)) {
            $dataParameter = new DataParameter($value);
            $dataParameterNames[] = $dataParameter->getProperty();
        }
    }
}
