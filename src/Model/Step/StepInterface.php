<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Step;

use webignition\BasilModels\Model\DataSet\DataSetCollectionInterface;
use webignition\BasilModels\Model\Statement\Action\ActionCollectionInterface;
use webignition\BasilModels\Model\Statement\Assertion\AssertionCollectionInterface;

interface StepInterface
{
    public function getActions(): ActionCollectionInterface;

    public function withActions(ActionCollectionInterface $actions): StepInterface;

    public function getAssertions(): AssertionCollectionInterface;

    public function withAssertions(AssertionCollectionInterface $assertions): StepInterface;

    public function getData(): ?DataSetCollectionInterface;

    public function withData(DataSetCollectionInterface $data): StepInterface;

    public function getImportName(): ?string;

    public function withImportName(string $importName): StepInterface;

    public function removeImportName(): StepInterface;

    public function getDataImportName(): ?string;

    public function withDataImportName(string $dataImportName): StepInterface;

    public function removeDataImportName(): StepInterface;

    /**
     * @return array<string, string>
     */
    public function getIdentifiers(): array;

    /**
     * @param array<string, string> $identifiers
     */
    public function withIdentifiers(array $identifiers): Step;

    public function requiresImportResolution(): bool;

    public function withPrependedActions(ActionCollectionInterface $actions): StepInterface;

    public function withPrependedAssertions(AssertionCollectionInterface $assertions): StepInterface;

    /**
     * @return string[]
     */
    public function getDataParameterNames(): array;
}
