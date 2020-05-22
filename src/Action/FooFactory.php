<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\UnknownEncapsulatedStatementException;

class FooFactory
{
    public static function createFactory(): self
    {
        return new FooFactory();
    }

    /**
     * @param array<mixed> $data
     *
     * @return FooActionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromArray(array $data): FooActionInterface
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

        return new FooAction($source, $type, $arguments, $identifier, $value);
    }

    /**
     * @param string $json
     *
     * @return FooActionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): FooActionInterface
    {
        return $this->createFromArray(json_decode($json, true));
    }

    /**
     * @param array<mixed> $containerData
     * @param array<mixed> $statementData
     *
     * @return FooActionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createEncapsulatingAction(array $containerData, array $statementData): FooActionInterface
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
     * @return FooResolvedActionInterface
     *
     * @throws UnknownEncapsulatedStatementException
     */
    private function createResolvedAction(array $containerData, array $statementData): FooResolvedActionInterface
    {
        $sourceAction = $this->createFromArray($statementData);

        $identifier = array_key_exists('identifier', $containerData)
            ? (string) $containerData['identifier']
            : null;

        $value = array_key_exists('value', $containerData)
            ? (string) $containerData['value']
            : null;

        return new FooResolvedAction($sourceAction, $identifier, $value);
    }
}
