<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

use webignition\BasilModels\Model\ArrayAccessor;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

class Factory
{
    public static function createFactory(): self
    {
        return new Factory();
    }

    /**
     * @param array<mixed> $data
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromArray(array $data): ActionInterface
    {
        $containerData = $data['container'] ?? null;
        $statementData = $data['statement'] ?? null;

        if (is_array($containerData) && is_array($statementData)) {
            return $this->createEncapsulatingAction($containerData, $statementData);
        }

        $source = ArrayAccessor::getStringValue($data, 'source');
        $type = ArrayAccessor::getStringValue($data, 'type');

        $arguments = array_key_exists('arguments', $data)
            ? (string) $data['arguments']
            : null;

        $identifier = array_key_exists('identifier', $data)
            ? (string) $data['identifier']
            : null;

        $value = array_key_exists('value', $data)
            ? (string) $data['value']
            : null;

        return new Action($source, $type, $arguments, $identifier, $value);
    }

    /**
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): ActionInterface
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
    private function createEncapsulatingAction(array $containerData, array $statementData): ActionInterface
    {
        $containerType = $containerData['type'] ?? null;

        if ('resolved-action' === $containerType) {
            return $this->createResolvedAction($containerData, $statementData);
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
     * @throws UnknownEncapsulatedStatementException
     */
    private function createResolvedAction(array $containerData, array $statementData): EncapsulatingActionInterface
    {
        $sourceAction = $this->createFromArray($statementData);

        $identifier = array_key_exists('identifier', $containerData)
            ? (string) $containerData['identifier']
            : null;

        $value = array_key_exists('value', $containerData)
            ? (string) $containerData['value']
            : null;

        return new ResolvedAction($sourceAction, $identifier, $value);
    }
}
