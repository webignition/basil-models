<?php

declare(strict_types=1);

namespace webignition\BasilModels\DataSet;

class DataSet implements DataSetInterface
{
    private string $name;

    /**
     * @var array<int|string, string>
     */
    private array $data;

    /**
     * @param string $name
     * @param array<int|string, string> $data
     */
    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->data = [];

        foreach ($data as $key => $value) {
            $this->data[$key] = (string) $value;
        }
    }

    /**
     * @param array<mixed> $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new DataSet(
            (string) ($data['name'] ?? ''),
            $data['data'] ?? []
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int|string, string>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string[]
     */
    public function getParameterNames(): array
    {
        $keys = [];

        foreach (array_keys($this->data) as $key) {
            $keys[] = (string) $key;
        }

        asort($keys);

        return array_values($keys);
    }

    /**
     * @param string[] $parameterNames
     *
     * @return bool
     */
    public function hasParameterNames(array $parameterNames): bool
    {
        $dataSetParameterNames = $this->getParameterNames();

        foreach ($parameterNames as $parameterName) {
            if (!in_array($parameterName, $dataSetParameterNames)) {
                return false;
            }
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'data' => $this->data,
        ];
    }
}
