<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

class UnknownComparisonException extends MalformedDataException
{
    private $comparison;

    /**
     * @param array<mixed> $data
     * @param string $comparison
     */
    public function __construct(array $data, string $comparison)
    {
        $this->comparison = $comparison;

        parent::__construct($data);
    }

    public function getComparison(): string
    {
        return $this->comparison;
    }
}
