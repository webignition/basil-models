<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\DataSet\DataSetCollection;
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

    public function withData(DataSetCollection $data): StepInterface
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

    private function setActions(array $actions)
    {
        $this->actions = $this->filterActions($actions);
    }

    private function setAssertions(array $assertions)
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
}
