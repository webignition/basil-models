<?php

declare(strict_types=1);

namespace webignition\BasilModels;

class EncapsulatingStatementData
{
    public const KEY_ENCAPSULATION = 'encapsulation';
    public const KEY_ENCAPSULATION_CONTAINER = 'container';
    public const KEY_ENCAPSULATES = 'encapsulates';

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
            self::KEY_ENCAPSULATION => array_merge(
                [
                    self::KEY_ENCAPSULATION_CONTAINER => $this->containerType,
                ],
                $this->encapsulationData
            ),
            self::KEY_ENCAPSULATES => $this->sourceData,
        ];
    }
}
