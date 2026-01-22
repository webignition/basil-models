<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement;

use webignition\BasilModels\Enum\EncapsulatingStatementType;
use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\Statement\Action\Factory as ActionFactory;
use webignition\BasilModels\Model\Statement\Assertion\Factory as AssertionFactory;

readonly class StatementFactory
{
    public function __construct(
        private ActionFactory $actionFactory,
        private AssertionFactory $assertionFactory,
    ) {}

    public static function createFactory(): StatementFactory
    {
        return new StatementFactory(
            ActionFactory::createFactory(),
            AssertionFactory::createFactory(),
        );
    }

    /**
     * @param array<mixed> $data
     *
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromArray(array $data): ?StatementInterface
    {
        $statementType = $this->getStatementType($data);

        if (
            StatementType::ACTION->value === $statementType
            || EncapsulatingStatementType::RESOLVED_ACTION->value === $statementType
        ) {
            return $this->actionFactory->createFromArray($data);
        }

        if (
            StatementType::ASSERTION->value === $statementType
            || EncapsulatingStatementType::RESOLVED_ASSERTION->value === $statementType
            || EncapsulatingStatementType::DERIVED_VALUE_OPERATION_ASSERTION->value === $statementType
        ) {
            return $this->assertionFactory->createFromArray($data);
        }

        return null;
    }

    /**
     * @throws UnknownEncapsulatedStatementException
     * @throws InvalidStatementDataException
     */
    public function createFromJson(string $json): StatementInterface
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new InvalidStatementDataException($json);
        }

        $statement = $this->createFromArray($data);
        if (null === $statement) {
            throw new InvalidStatementDataException($json);
        }

        return $statement;
    }

    /**
     * @param array<mixed> $data
     */
    private function getStatementType(array $data): ?string
    {
        $statementType = $data['statement-type'] ?? null;
        if (is_string($statementType)) {
            return $statementType;
        }

        $containerData = $data['container'] ?? null;
        if (!is_array($containerData)) {
            return null;
        }

        $containerType = $containerData['type'] ?? null;
        if (!is_string($containerType)) {
            return null;
        }

        return $containerType;
    }
}
