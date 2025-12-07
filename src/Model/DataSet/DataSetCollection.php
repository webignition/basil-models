<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\DataSet;

class DataSetCollection implements DataSetCollectionInterface
{
    /**
     * @var DataSet[]
     */
    private array $dataSets = [];

    private int $iteratorPosition;

    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->iteratorPosition = 0;

        foreach ($data as $dataSetName => $dataSet) {
            if (is_array($dataSet)) {
                $this->dataSets[] = new DataSet((string) $dataSetName, $this->filterDataSet($dataSet));
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

    /**
     * @return array<string, array<int|string, string>>
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this as $dataSet) {
            if ($dataSet instanceof DataSetInterface) {
                $data[$dataSet->getName()] = $dataSet->getData();
            }
        }

        return $data;
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

    /**
     * @param array<mixed> $dataSet
     *
     * @return array<int|string, string>
     */
    private function filterDataSet(array $dataSet): array
    {
        $filteredDataSet = [];
        foreach ($dataSet as $key => $value) {
            if (is_string($value)) {
                $filteredDataSet[(string) $key] = $value;
            }
        }

        return $filteredDataSet;
    }
}
