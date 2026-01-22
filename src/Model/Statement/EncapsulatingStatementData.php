<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Statement;

use webignition\BasilModels\Enum\EncapsulatingStatementType;
use webignition\BasilModels\Model\Statement\Action\ActionInterface;
use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;

/**
 * @phpstan-import-type SerializedStatement from StatementInterface
 * @phpstan-import-type SerializedAction from ActionInterface
 * @phpstan-import-type SerializedAssertion from AssertionInterface
 */
class EncapsulatingStatementData
{
    public const KEY_CONTAINER = 'container';
    public const KEY_CONTAINER_TYPE = 'type';
    public const KEY_STATEMENT = 'statement';

    /**
     * @var SerializedAction|SerializedAssertion|SerializedStatement
     */
    private array $sourceData;

    /**
     * @param array{'identifier'?: ?string, 'value'?: string, 'operator'?: string} $encapsulationData
     */
    public function __construct(
        StatementInterface $statement,
        private readonly EncapsulatingStatementType $containerType,
        private readonly array $encapsulationData
    ) {
        $this->sourceData = $statement->jsonSerialize();
    }

    /**
     * @return array{
     *     'container': array{
     *         'type': value-of<EncapsulatingStatementType>,
     *         'identifier'?: ?string,
     *         'value'?: string,
     *         'operator'?: string
     *     },
     *     'statement': SerializedAction|SerializedAssertion|SerializedStatement
     * }
     */
    public function jsonSerialize(): array
    {
        $containerData = $this->encapsulationData;
        $containerData[self::KEY_CONTAINER_TYPE] = $this->containerType->value;

        return [
            self::KEY_CONTAINER => $containerData,
            self::KEY_STATEMENT => $this->sourceData,
        ];
    }
}
