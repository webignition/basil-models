<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

use webignition\BasilModels\Action\Factory\Factory as ActionFactory;
use webignition\BasilModels\Action\Factory\MalformedDataException as MalformedActionDataException;
use webignition\BasilModels\Action\Factory\UnknownActionTypeException;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\Factory\MalformedDataException as MalformedAssertionDataException;

class Factory
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

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
     *
     * @throws MalformedActionDataException
     * @throws MalformedDataException
     * @throws UnknownActionTypeException
     * @throws UnknownComparisonException
     */
    public function createFromArray(array $assertionData): AssertionInterface
    {
        if (null !== ($assertionData[DerivedValueOperationAssertion::KEY_SOURCE_TYPE] ?? null)) {
            return $this->createDerivedValueOperationAssertionFromArray($assertionData);
        }

        $comparison = $assertionData[Assertion::KEY_COMPARISON] ?? '';

        if (Assertion::createsFromComparison($comparison)) {
            $assertion = Assertion::fromArray($assertionData);

            if ($assertion instanceof AssertionInterface) {
                return $assertion;
            }

            throw new MalformedAssertionDataException($assertionData);
        }

        if (ComparisonAssertion::createsFromComparison($comparison)) {
            $assertion = ComparisonAssertion::fromArray($assertionData);

            if ($assertion instanceof AssertionInterface) {
                return $assertion;
            }

            throw new MalformedAssertionDataException($assertionData);
        }

        throw new UnknownComparisonException($assertionData, $comparison);
    }

    /**
     * @param string $json
     *
     * @return AssertionInterface
     *
     * @throws MalformedActionDataException
     * @throws MalformedDataException
     * @throws UnknownActionTypeException
     * @throws UnknownComparisonException
     */
    public function createFromJson(string $json): AssertionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $assertionData
     *
     * @return DerivedValueOperationAssertion
     *
     * @throws MalformedActionDataException
     * @throws MalformedAssertionDataException
     * @throws UnknownActionTypeException
     * @throws UnknownComparisonException
     */
    private function createDerivedValueOperationAssertionFromArray(array $assertionData): DerivedValueOperationAssertion
    {
        $operator = $assertionData[DerivedValueOperationAssertion::KEY_OPERATOR] ?? '';
        if ('' === $operator) {
            throw new MalformedAssertionDataException($assertionData);
        }

        $value = $assertionData[DerivedValueOperationAssertion::KEY_VALUE] ?? '';
        if ('' === $value) {
            throw new MalformedAssertionDataException($assertionData);
        }

        $source = $assertionData[DerivedValueOperationAssertion::KEY_SOURCE] ?? [];
        if ([] === $source) {
            throw new MalformedAssertionDataException($assertionData);
        }

        $sourceType = $assertionData[DerivedValueOperationAssertion::KEY_SOURCE_TYPE];

        $sourceStatement = 'action' === $sourceType
            ? $this->actionFactory->createFromArray($source)
            : $this->createFromArray($source);

        return new DerivedValueOperationAssertion($sourceStatement, $value, $operator);
    }
}
