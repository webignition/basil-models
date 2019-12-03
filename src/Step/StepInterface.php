<?php

declare(strict_types=1);

namespace webignition\BasilModels\Step;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\DataSet\DataSetCollection;
use webignition\BasilModels\DataSet\DataSetCollectionInterface;

interface StepInterface
{
    /**
     * @return ActionInterface[]
     */
    public function getActions(): array;

    /**
     * @param ActionInterface[] $actions
     *
     * @return StepInterface
     */
    public function withActions(array $actions): StepInterface;

    /**
     * @return AssertionInterface[]
     */
    public function getAssertions(): array;

    /**
     * @param AssertionInterface[] $assertions
     *
     * @return StepInterface
     */
    public function withAssertions(array $assertions): StepInterface;

    public function getData(): ?DataSetCollectionInterface;
    public function withData(DataSetCollection $data): StepInterface;

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

    public function withIdentifiers(array $elements): Step;

    public function requiresImportResolution(): bool;

    /**
     * @param ActionInterface[] $actions
     *
     * @return StepInterface
     */
    public function withPrependedActions(array $actions): StepInterface;

    /**
     * @param AssertionInterface[] $assertions
     *
     * @return StepInterface
     */
    public function withPrependedAssertions(array $assertions): StepInterface;
}
