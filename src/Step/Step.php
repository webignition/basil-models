<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\DataParameter\DataParameter;
use webignition\BasilModels\DataSet\DataSetCollectionInterface;

class Step implements StepInterface
{
    /**
     * @var ActionInterface[]
     */
    private array $actions;

    /**
     * @var AssertionInterface[]
     */
    private array $assertions;

    private ?DataSetCollectionInterface $data = null;
    private ?string $importName = null;
    private ?string $dataImportName = null;

    /**
     * @var string[]
     */
    private array $identifiers;

    /**
     * @param array<mixed> $actions
     * @param array<mixed> $assertions
     */
    public function __construct(array $actions, array $assertions)
    {
        $this->actions = [];
        $this->assertions = [];
        $this->identifiers = [];

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

    /**
     * @param string[] $identifiers
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
     * @param array<mixed> $assertions
     */
    private function setAssertions(array $assertions): void
    {
        $this->assertions = $this->filterAssertions($assertions);
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
     * @param array<mixed> $assertions
     *
     * @return AssertionInterface[]
     */
    private function filterAssertions(array $assertions): array
    {
        return array_filter($assertions, function ($assertion) {
            return $assertion instanceof AssertionInterface;
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
