<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\Action\Factory as ActionFactory;
use webignition\BasilModels\StatementInterface;
use webignition\BasilModels\UnknownEncapsulatedStatementException;

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
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromArray(array $data): AssertionInterface
    {
        $assertion = $this->createPossibleEncapsulatingAssertion($data);
        if ($assertion instanceof AssertionInterface) {
            return $assertion;
        }

        $source = (string) ($data['source'] ?? '');
        $identifier = (string) ($data['identifier'] ?? '');
        $operator = (string) ($data['operator'] ?? '');

        $value = array_key_exists('value', $data)
            ? (string) $data['value']
            : null;

        return new Assertion($source, $identifier, $operator, $value);
    }

    /**
     * @param string $json
     *
     * @return AssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): AssertionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return AssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createEncapsulatingAssertion(array $containerData, array $statementData): AssertionInterface
    {
        $containerType = $containerData['type'] ?? null;

        if ('derived-value-operation-assertion' === $containerType) {
            return $this->createDerivedValueOperationAssertion($containerData, $statementData);
        }

        if ('resolved-assertion' === $containerType) {
            return $this->createResolvedAssertion($containerData, $statementData);
        }

        throw new UnknownEncapsulatedStatementException([
            'container' => $containerData,
            'statement' => $statementData,
        ]);
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return DerivedValueOperationAssertion
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createDerivedValueOperationAssertion(
        array $containerData,
        array $statementData
    ): DerivedValueOperationAssertion {
        return new DerivedValueOperationAssertion(
            $this->createStatement($statementData),
            (string) ($containerData['value'] ?? ''),
            (string) ($containerData['operator'] ?? '')
        );
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return ResolvedAssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createResolvedAssertion(
        array $containerData,
        array $statementData
    ): ResolvedAssertionInterface {
        $sourceAssertion = $this->createFromArray($statementData);

        $identifier = (string) ($containerData['identifier'] ?? '');

        $value = array_key_exists('value', $containerData)
            ? (string) $containerData['value']
            : null;

        return new ResolvedAssertion($sourceAssertion, $identifier, $value);
    }

    /**
     * @param array<mixed> $data
     *
     * @return StatementInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createStatement(array $data): StatementInterface
    {
        $assertion = $this->createPossibleEncapsulatingAssertion($data);
        if ($assertion instanceof AssertionInterface) {
            return $assertion;
        }

        $type = $data['statement-type'];

        return 'action' === $type
            ? $this->actionFactory->createFromArray($data)
            : $this->createFromArray($data);
    }

    /**
     * @param array<mixed> $data
     *
     * @return AssertionInterface|null
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createPossibleEncapsulatingAssertion(array $data): ?AssertionInterface
    {
        $containerData = $data['container'] ?? null;
        $statementData = $data['statement'] ?? null;

        if (is_array($containerData) && is_array($statementData)) {
            return $this->createEncapsulatingAssertion($containerData, $statementData);
        }

        return null;
    }
}
