<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\Action\FooFactory as ActionFactory;
use webignition\BasilModels\FooStatementInterface;
use webignition\BasilModels\UnknownEncapsulatedStatementException;

class FooFactory
{
    private ActionFactory $actionFactory;

    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    public static function createFactory(): self
    {
        return new FooFactory(
            ActionFactory::createFactory()
        );
    }

    /**
     * @param array<mixed> $data
     *
     * @return FooAssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromArray(array $data): FooAssertionInterface
    {
        $containerData = $data['container'] ?? null;
        $statementData = $data['statement'] ?? null;

        if (is_array($containerData) && is_array($statementData)) {
            return $this->createEncapsulatingAssertion($containerData, $statementData);
        }

        $source = (string) ($data['source'] ?? '');
        $identifier = (string) ($data['identifier'] ?? '');
        $operator = (string) ($data['operator'] ?? '');

        $value = array_key_exists('value', $data)
            ? (string) $data['value']
            : null;

        return new FooAssertion($source, $identifier, $operator, $value);
    }

    /**
     * @param string $json
     *
     * @return FooAssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): FooAssertionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return FooAssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createEncapsulatingAssertion(array $containerData, array $statementData): FooAssertionInterface
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
     * @return FooDerivedValueOperationAssertion
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createDerivedValueOperationAssertion(
        array $containerData,
        array $statementData
    ): FooDerivedValueOperationAssertion {
        return new FooDerivedValueOperationAssertion(
            $this->createStatement($statementData),
            (string) ($containerData['value'] ?? ''),
            (string) ($containerData['operator'] ?? '')
        );
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return FooResolvedAssertionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createResolvedAssertion(
        array $containerData,
        array $statementData
    ): FooResolvedAssertionInterface {
        $sourceAssertion = $this->createFromArray($statementData);

        $identifier = (string) ($containerData['identifier'] ?? '');

        $value = array_key_exists('value', $containerData)
            ? (string) $containerData['value']
            : null;

        return new FooResolvedAssertion($sourceAssertion, $identifier, $value);
    }

    /**
     * @param array<mixed> $statementData
     *
     * @return FooStatementInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createStatement(array $statementData): FooStatementInterface
    {
        $type = $statementData['statement-type'];

        return 'action' === $type
            ? $this->actionFactory->createFromArray($statementData)
            : $this->createFromArray($statementData);
    }
}
