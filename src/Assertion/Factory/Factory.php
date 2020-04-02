<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;

class Factory
{
    /**
     * @param array<mixed> $assertionData
     *
     * @return AssertionInterface
     *
     * @throws UnknownComparisonException
     * @throws MalformedDataException
     */
    public function createFromArray(array $assertionData): AssertionInterface
    {
        $comparison = $assertionData[Assertion::KEY_COMPARISON] ?? '';

        if (Assertion::createsFromComparison($comparison)) {
            $assertion = Assertion::fromArray($assertionData);

            if ($assertion instanceof AssertionInterface) {
                return $assertion;
            }

            throw new MalformedDataException($assertionData);
        }

        if (ComparisonAssertion::createsFromComparison($comparison)) {
            $assertion = ComparisonAssertion::fromArray($assertionData);

            if ($assertion instanceof AssertionInterface) {
                return $assertion;
            }

            throw new MalformedDataException($assertionData);
        }

        throw new UnknownComparisonException($assertionData, $comparison);
    }
}
