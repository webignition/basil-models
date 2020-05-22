<?php

declare(strict_types=1);

namespace webignition\BasilModels;

class EncapsulatingStatementData
{
    public const KEY_CONTAINER = 'container';
    public const KEY_CONTAINER_TYPE = 'type';
    public const KEY_STATEMENT = 'statement';

    private string $containerType;

    /**
     * @var array<mixed>
     */
    private array $encapsulationData = [];

    /**
     * @var array<mixed>
     */
    private array $sourceData = [];

    /**
     * @param FooStatementInterface $statement
     * @param string $containerType
     * @param array<mixed> $encapsulationData
     */
    public function __construct(FooStatementInterface $statement, string $containerType, array $encapsulationData)
    {
        $this->containerType = $containerType;
        $this->encapsulationData = $encapsulationData;

        $this->sourceData = $statement->jsonSerialize();
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            self::KEY_CONTAINER => array_merge(
                [
                    self::KEY_CONTAINER_TYPE => $this->containerType,
                ],
                $this->encapsulationData
            ),
            self::KEY_STATEMENT => $this->sourceData,
        ];
    }
}
