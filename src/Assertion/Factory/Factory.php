<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

use webignition\BasilModels\Action\Factory\Factory as ActionFactory;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;

class Factory
{
    private ActionFactory $actionFactory;

    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    public static function createFactory(): self
    {
        return new Factory(
            ActionFactory::createFactory()
        );
    }

    /**
     * @param array<mixed> $assertionData
     *
     * @return AssertionInterface
     */
    public function createFromArray(array $assertionData): AssertionInterface
    {
        if (null !== ($assertionData[DerivedValueOperationAssertion::KEY_SOURCE_TYPE] ?? null)) {
            return $this->createDerivedValueOperationAssertionFromArray($assertionData);
        }

        $comparison = $assertionData[Assertion::KEY_COMPARISON] ?? '';

        if (in_array($comparison, ['is', 'is-not', 'includes', 'excludes', 'matches'])) {
            return ComparisonAssertion::fromArray($assertionData);
        }

        return Assertion::fromArray($assertionData);
    }

    /**
     * @param string $json
     *
     * @return AssertionInterface
     */
    public function createFromJson(string $json): AssertionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $assertionData
     *
     * @return DerivedValueOperationAssertion
     */
    private function createDerivedValueOperationAssertionFromArray(array $assertionData): DerivedValueOperationAssertion
    {
        $source = $assertionData[DerivedValueOperationAssertion::KEY_SOURCE] ?? [];
        $sourceType = $assertionData[DerivedValueOperationAssertion::KEY_SOURCE_TYPE];

        $sourceStatement = 'action' === $sourceType
            ? $this->actionFactory->createFromArray($source)
            : $this->createFromArray($source);

        return new DerivedValueOperationAssertion(
            $sourceStatement,
            (string) ($assertionData[DerivedValueOperationAssertion::KEY_VALUE] ?? ''),
            (string) ($assertionData[DerivedValueOperationAssertion::KEY_OPERATOR] ?? '')
        );
    }
}
