<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataSet;

interface DataSetInterface
{
    public function getName(): string;
    public function getData(): array;

    /**
     * @return string[]
     */
    public function getParameterNames(): array;

    public function hasParameterNames(array $parameterNames): bool;
}
