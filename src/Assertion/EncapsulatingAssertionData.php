<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\StatementInterface;

class EncapsulatingAssertionData
{
    public const KEY_ENCAPSULATION = 'encapsulation';
    public const KEY_ENCAPSULATION_TYPE = 'type';
    public const KEY_ENCAPSULATION_SOURCE_TYPE = 'source_type';
    public const KEY_ENCAPSULATES = 'encapsulates';

    private string $encapsulationType;

    /**
     * @var array<mixed>
     */
    private array $encapsulationData = [];

    private string $sourceType = '';

    /**
     * @var array<mixed>
     */
    private array $sourceData = [];

    /**
     * @param StatementInterface $statement
     * @param string $encapsulationType
     * @param array<mixed> $encapsulationData
     */
    public function __construct(StatementInterface $statement, string $encapsulationType, array $encapsulationData)
    {
        $this->encapsulationType = $encapsulationType;
        $this->encapsulationData = $encapsulationData;

        $this->sourceType = $statement instanceof AssertionInterface ? 'assertion' : 'action';
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
                    self::KEY_ENCAPSULATION_TYPE => $this->encapsulationType,
                    self::KEY_ENCAPSULATION_SOURCE_TYPE => $this->sourceType,
                ],
                $this->encapsulationData
            ),
            self::KEY_ENCAPSULATES => $this->sourceData,
        ];
    }
}
