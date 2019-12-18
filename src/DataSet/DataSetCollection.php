<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataSet;

class DataSetCollection implements DataSetCollectionInterface
{
    /**
     * @var DataSet[]
     */
    private $dataSets = [];

    private $iteratorPosition = 0;

    public function __construct(array $data)
    {
        $this->iteratorPosition = 0;

        foreach ($data as $dataSetName => $dataSet) {
            if (is_array($dataSet)) {
                $this->dataSets[] = new DataSet((string) $dataSetName, $dataSet);
            }
        }
    }

    /**
     * @return string[]
     */
    public function getParameterNames(): array
    {
        $firstDataSet = $this->dataSets[0] ?? null;

        if (null === $firstDataSet) {
            return [];
        }

        return $firstDataSet->getParameterNames();
    }

    // \Countable methods

    public function count(): int
    {
        return count($this->dataSets);
    }

    // Iterator methods

    public function rewind(): void
    {
        $this->iteratorPosition = 0;
    }

    public function current(): ?DataSetInterface
    {
        return $this->dataSets[$this->iteratorPosition] ?? null;
    }

    public function key(): int
    {
        return $this->iteratorPosition;
    }

    public function next(): void
    {
        ++$this->iteratorPosition;
    }

    public function valid(): bool
    {
        return isset($this->dataSets[$this->iteratorPosition]);
    }
}
