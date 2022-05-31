<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Action\Factory as ActionFactory;
use webignition\BasilModels\Model\ArrayAccessor;
use webignition\BasilModels\Model\StatementInterface;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

class Factory
{
    public function __construct(
        private readonly ActionFactory $actionFactory
    ) {
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
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromArray(array $data): AssertionInterface
    {
        $assertion = $this->createPossibleEncapsulatingStatement($data);
        if ($assertion instanceof AssertionInterface) {
            return $assertion;
        }

        $source = ArrayAccessor::getStringValue($data, 'source');
        $identifier = ArrayAccessor::getStringValue($data, 'identifier');
        $operator = ArrayAccessor::getStringValue($data, 'operator');

        $value = array_key_exists('value', $data)
            ? (string) $data['value']
            : null;

        return new Assertion($source, $identifier, $operator, $value);
    }

    /**
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): AssertionInterface
    {
        $data = json_decode($json, true);
        $data = is_array($data) ? $data : [];

        return $this->createFromArray($data);
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createDerivedValueOperationAssertion(
        array $containerData,
        array $statementData
    ): DerivedValueOperationAssertion {
        return new DerivedValueOperationAssertion(
            $this->createStatement($statementData),
            ArrayAccessor::getStringValue($containerData, 'value'),
            ArrayAccessor::getStringValue($containerData, 'operator')
        );
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createResolvedAssertion(
        array $containerData,
        array $statementData
    ): EncapsulatingAssertionInterface {
        $sourceAssertion = $this->createFromArray($statementData);

        $identifier = ArrayAccessor::getStringValue($containerData, 'identifier');

        $value = array_key_exists('value', $containerData)
            ? (string) $containerData['value']
            : null;

        return new ResolvedAssertion($sourceAssertion, $identifier, $value);
    }

    /**
     * @param array<mixed> $data
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createStatement(array $data): StatementInterface
    {
        $assertion = $this->createPossibleEncapsulatingStatement($data);
        if ($assertion instanceof AssertionInterface) {
            return $assertion;
        }

        $action = $this->createPossibleEncapsulatingStatement($data);
        if ($action instanceof ActionInterface) {
            return $action;
        }

        $type = $data['statement-type'];

        return 'action' === $type
            ? $this->actionFactory->createFromArray($data)
            : $this->createFromArray($data);
    }

    /**
     * @param array<mixed> $data
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createPossibleEncapsulatingStatement(array $data): ?StatementInterface
    {
        $containerData = $data['container'] ?? null;
        $statementData = $data['statement'] ?? null;

        if (is_array($containerData) && is_array($statementData)) {
            return $this->createEncapsulatingStatement($containerData, $statementData);
        }

        return null;
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createEncapsulatingStatement(array $containerData, array $statementData): StatementInterface
    {
        $containerType = $containerData['type'] ?? null;

        if ('derived-value-operation-assertion' === $containerType) {
            return $this->createDerivedValueOperationAssertion($containerData, $statementData);
        }

        if ('resolved-assertion' === $containerType) {
            return $this->createResolvedAssertion($containerData, $statementData);
        }

        if ('resolved-action' === $containerType) {
            return $this->actionFactory->createFromArray([
                'container' => $containerData,
                'statement' => $statementData,
            ]);
        }

        throw new UnknownEncapsulatedStatementException([
            'container' => $containerData,
            'statement' => $statementData,
        ]);
    }
}
