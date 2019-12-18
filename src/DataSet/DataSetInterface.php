<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataSet;

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
     *
     * @return bool
     */
    public function hasParameterNames(array $parameterNames): bool;
}
