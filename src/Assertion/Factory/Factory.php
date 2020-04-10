<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

use webignition\BasilModels\Action\Factory\Factory as ActionFactory;
use webignition\BasilModels\Action\Factory\MalformedDataException as MalformedActionDataException;
use webignition\BasilModels\Action\Factory\UnknownActionTypeException;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedElementExistsAssertion;
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
        if (null !== ($assertionData[DerivedElementExistsAssertion::KEY_SOURCE_TYPE] ?? null)) {
            return $this->createDerivedElementExistsAssertionFromArray($assertionData);
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
     * @return DerivedElementExistsAssertion
     *
     * @throws MalformedActionDataException
     * @throws MalformedAssertionDataException
     * @throws UnknownActionTypeException
     * @throws UnknownComparisonException
     */
    private function createDerivedElementExistsAssertionFromArray(array $assertionData): DerivedElementExistsAssertion
    {
        $identifier = $assertionData[DerivedElementExistsAssertion::KEY_IDENTIFIER] ?? '';
        if ('' === $identifier) {
            throw new MalformedAssertionDataException($assertionData);
        }

        $source = $assertionData[DerivedElementExistsAssertion::KEY_SOURCE] ?? [];
        if ([] === $source) {
            throw new MalformedAssertionDataException($assertionData);
        }

        $sourceType = $assertionData[DerivedElementExistsAssertion::KEY_SOURCE_TYPE];

        if ('action' === $sourceType) {
            return new DerivedElementExistsAssertion(
                $this->actionFactory->createFromArray($source),
                $identifier
            );
        }

        return new DerivedElementExistsAssertion(
            $this->createFromArray($source),
            $identifier
        );
    }
}
