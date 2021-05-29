<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\DataSet\DataSetCollectionInterface;

interface StepInterface
{
    /**
     * @return ActionInterface[]
     */
    public function getActions(): array;

    /**
     * @param ActionInterface[] $actions
     */
    public function withActions(array $actions): StepInterface;

    /**
     * @return AssertionInterface[]
     */
    public function getAssertions(): array;

    /**
     * @param AssertionInterface[] $assertions
     */
    public function withAssertions(array $assertions): StepInterface;

    public function getData(): ?DataSetCollectionInterface;
    public function withData(DataSetCollectionInterface $data): StepInterface;

    public function getImportName(): ?string;
    public function withImportName(string $importName): StepInterface;
    public function removeImportName(): StepInterface;

    public function getDataImportName(): ?string;
    public function withDataImportName(string $dataImportName): StepInterface;
    public function removeDataImportName(): StepInterface;

    /**
     * @return string[]
     */
    public function getIdentifiers(): array;

    /**
     * @param string[] $identifiers
     */
    public function withIdentifiers(array $identifiers): Step;

    public function requiresImportResolution(): bool;

    /**
     * @param ActionInterface[] $actions
     */
    public function withPrependedActions(array $actions): StepInterface;

    /**
     * @param AssertionInterface[] $assertions
     */
    public function withPrependedAssertions(array $assertions): StepInterface;

    /**
     * @return string[]
     */
    public function getDataParameterNames(): array;
}
