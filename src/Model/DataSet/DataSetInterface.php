<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\DataSet;

interface DataSetInterface
{
    public function getName(): string;

    /**
     * @return array<int|string, string>
     */
    public function getData(): array;

    /**
     * @return string[]
     */
    public function getParameterNames(): array;

    /**
     * @param string[] $parameterNames
     */
    public function hasParameterNames(array $parameterNames): bool;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
