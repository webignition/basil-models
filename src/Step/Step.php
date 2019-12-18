<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\InputActionInterface;
use webignition\BasilModels\Action\InteractionActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertionInterface;
use webignition\BasilModels\DataParameter\DataParameter;
use webignition\BasilModels\DataSet\DataSetCollectionInterface;

class Step implements StepInterface
{
    private $actions = [];
    private $assertions = [];
    private $data = null;
    private $importName = null;
    private $dataImportName = null;
    private $identifiers = [];

    public function __construct(array $actions, array $assertions)
    {
        $this->setActions($actions);
        $this->setAssertions($assertions);
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
     *
     * @return StepInterface
     */
    public function withActions(array $actions): StepInterface
    {
        $new = clone $this;
        $new->setActions($actions);

        return $new;
    }

    /**
     * @return AssertionInterface[]
     */
    public function getAssertions(): array
    {
        return $this->assertions;
    }

    /**
     * @param AssertionInterface[] $assertions
     *
     * @return StepInterface
     */
    public function withAssertions(array $assertions): StepInterface
    {
        $new = clone $this;
        $new->setAssertions($assertions);

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
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    public function withIdentifiers(array $elements): Step
    {
        $new = clone $this;
        $new->identifiers = $elements;

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

    public function withPrependedAssertions(array $assertions): StepInterface
    {
        $assertions = $this->filterAssertions($assertions);

        foreach ($this->getAssertions() as $assertion) {
            $assertions[] = clone $assertion;
        }

        $new = clone $this;
        $new->assertions = $assertions;

        return $new;
    }

    public function getDataParameterNames(): array
    {
        $dataParameterNames = [];

        foreach ($this->getActions() as $action) {
            if ($action instanceof InteractionActionInterface) {
                $identifier = $action->getIdentifier();

                $this->addDataParameterName($identifier, $dataParameterNames);
            }

            if ($action instanceof InputActionInterface) {
                $value = $action->getValue();

                $this->addDataParameterName($value, $dataParameterNames);
            }
        }

        foreach ($this->getAssertions() as $assertion) {
            $identifier = $assertion->getIdentifier();

            $this->addDataParameterName($identifier, $dataParameterNames);

            if ($assertion instanceof ComparisonAssertionInterface) {
                $value = $assertion->getValue();

                $this->addDataParameterName($value, $dataParameterNames);
            }
        }

        $dataParameterNames = array_unique($dataParameterNames);
        sort($dataParameterNames);

        return $dataParameterNames;
    }

    private function setActions(array $actions): void
    {
        $this->actions = $this->filterActions($actions);
    }

    private function setAssertions(array $assertions): void
    {
        $this->assertions = $this->filterAssertions($assertions);
    }

    /**
     * @param array $actions
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
     * @param array $assertions
     *
     * @return AssertionInterface[]
     */
    private function filterAssertions(array $assertions): array
    {
        return array_filter($assertions, function ($assertion) {
            return $assertion instanceof AssertionInterface;
        });
    }

    private function addDataParameterName(string $value, array &$dataParameterNames): void
    {
        if (DataParameter::is($value)) {
            $dataParameter = new DataParameter($value);
            $dataParameterNames[] = $dataParameter->getProperty();
        }
    }
}
