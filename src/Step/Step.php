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
        foreach ($actions as $action) {
            if ($action instanceof ActionInterface) {
                $this->actions[] = $action;
            }
        }

        foreach ($assertions as $assertion) {
            if ($assertion instanceof AssertionInterface) {
                $this->assertions[] = $assertion;
            }
        }
    }

    /**
     * @return ActionInterface[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return AssertionInterface[]
     */
    public function getAssertions(): array
    {
        return $this->assertions;
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
}
