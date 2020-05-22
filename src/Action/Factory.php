<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\UnknownEncapsulatedStatementException;

class Factory
{
    public static function createFactory(): self
    {
        return new Factory();
    }

    /**
     * @param array<mixed> $data
     *
     * @return ActionInterface
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

        $source = (string) ($data['source'] ?? '');
        $type = (string) ($data['type'] ?? '');

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
     * @param string $json
     *
     * @return ActionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): ActionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return ActionInterface
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
     * @return ResolvedActionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createResolvedAction(array $containerData, array $statementData): ResolvedActionInterface
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
