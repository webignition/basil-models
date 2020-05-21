<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

use webignition\BasilModels\Action\Factory\Factory as ActionFactory;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\ComparisonAssertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\ResolvedAssertion;
use webignition\BasilModels\Assertion\ResolvedAssertionInterface;
use webignition\BasilModels\Assertion\ResolvedComparisonAssertion;
use webignition\BasilModels\Assertion\ResolvedComparisonAssertionInterface;

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
     * @param array<mixed> $data
     *
     * @return AssertionInterface
     *
     * @throws UnknownEncapsulatedAssertionException
     */
    public function createFromArray(array $data): AssertionInterface
    {
        $encapsulation = $data['encapsulation'] ?? null;

        if (null !== $encapsulation) {
            return $this->createFromEncapsulation($data);
        }

        $comparison = $data[Assertion::KEY_COMPARISON] ?? '';

        if (in_array($comparison, ['is', 'is-not', 'includes', 'excludes', 'matches'])) {
            return ComparisonAssertion::fromArray($data);
        }

        return Assertion::fromArray($data);
    }

    /**
     * @param string $json
     *
     * @return AssertionInterface
     *
     * @throws UnknownEncapsulatedAssertionException
     */
    public function createFromJson(string $json): AssertionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $data
     *
     * @return AssertionInterface
     *
     * @throws UnknownEncapsulatedAssertionException
     */
    private function createFromEncapsulation(array $data): AssertionInterface
    {
        $encapsulation = $data['encapsulation'] ?? [];
        $encapsulates = $data['encapsulates'] ?? [];

        $encapsulationType = $encapsulation['type'] ?? '';

        if ('derived-value-operation-assertion' === $encapsulationType) {
            return $this->createFromDerivedValueOperationAssertion($encapsulation, $encapsulates);
        }

        if ('resolved-assertion' === $encapsulationType) {
            return $this->createFromResolvedAssertion($encapsulation, $encapsulates);
        }

        if ('resolved-comparison-assertion' === $encapsulationType) {
            return $this->createFromResolvedComparisonAssertion($encapsulation, $encapsulates);
        }

        throw new UnknownEncapsulatedAssertionException($data);
    }

    /**
     * @param array<mixed> $encapsulation
     * @param array<mixed> $encapsulates
     *
     * @return DerivedValueOperationAssertion
     *
     * @throws UnknownEncapsulatedAssertionException
     */
    private function createFromDerivedValueOperationAssertion(
        array $encapsulation,
        array $encapsulates
    ): DerivedValueOperationAssertion {
        $sourceType = $encapsulation['source_type'] ?? '';

        $sourceStatement = 'action' === $sourceType
            ? $this->actionFactory->createFromArray($encapsulates)
            : $this->createFromArray($encapsulates);

        return new DerivedValueOperationAssertion(
            $sourceStatement,
            (string) ($encapsulation['value'] ?? ''),
            (string) ($encapsulation['operator'] ?? '')
        );
    }

    /**
     * @param array<mixed> $encapsulation
     * @param array<mixed> $encapsulates
     *
     * @return ResolvedAssertionInterface
     */
    private function createFromResolvedAssertion(
        array $encapsulation,
        array $encapsulates
    ): AssertionInterface {
        $sourceAssertion = Assertion::fromArray($encapsulates);

        return new ResolvedAssertion(
            $sourceAssertion,
            (string) ($encapsulation['source'] ?? ''),
            (string) ($encapsulation['identifier'] ?? '')
        );
    }

    /**
     * @param array<mixed> $encapsulation
     * @param array<mixed> $encapsulates
     *
     * @return ResolvedComparisonAssertionInterface
     */
    private function createFromResolvedComparisonAssertion(
        array $encapsulation,
        array $encapsulates
    ): AssertionInterface {
        $sourceAssertion = ComparisonAssertion::fromArray($encapsulates);

        return new ResolvedComparisonAssertion(
            $sourceAssertion,
            (string) ($encapsulation['source'] ?? ''),
            (string) ($encapsulation['identifier'] ?? ''),
            (string) ($encapsulation['value'] ?? '')
        );
    }
}
