<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model;

use webignition\BasilModels\Enum\EncapsulatingStatementType;
use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\Action\Factory as ActionFactory;
use webignition\BasilModels\Model\Assertion\Factory as AssertionFactory;

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
     * @throws UnknownEncapsulatedStatementException
     */
    public function createFromJson(string $json): ?StatementInterface
    {
        $data = json_decode($json, true);
        $data = is_array($data) ? $data : [];

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
