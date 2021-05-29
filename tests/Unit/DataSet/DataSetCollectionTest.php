<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\DataSet;

use webignition\BasilModels\DataSet\DataSet;
use webignition\BasilModels\DataSet\DataSetCollection;
use webignition\BasilModels\DataSet\DataSetInterface;

class DataSetCollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array<mixed> $data
     * @param DataSetInterface[] $expectedDataSets
     */
    public function testCreate(array $data, array $expectedDataSets): void
    {
        $dataSetCollection = new DataSetCollection($data);

        $this->assertCount(count($expectedDataSets), $dataSetCollection);

        foreach ($dataSetCollection as $index => $dataSet) {
            $expectedDataSet = $expectedDataSets[$index];

            $this->assertEquals($expectedDataSet, $dataSet);
        }
    }

    /**
     * @return array[]
     */
    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedDataSets' => [],
            ],
            'invalid' => [
                'data' => [
                    'set1' => true,
                    'set2' => 'string',
                ],
                'expectedDataSets' => [],
            ],
            'invalid and valid' => [
                'data' => [
                    'set1' => true,
                    'set2' => 'string',
                    'set3' => [
                        'key1' => 'key1value1',
                        'key2' => 'key2value1',
                    ],
                    'set4' => [
                        'key1' => 'key1value2',
                        'key2' => 'key2value2',
                    ],
                ],
                'expectedDataSets' => [
                    new DataSet('set3', [
                        'key1' => 'key1value1',
                        'key2' => 'key2value1',
                    ]),
                    new DataSet('set4', [
                        'key1' => 'key1value2',
                        'key2' => 'key2value2',
                    ]),
                ],
            ],
        ];
    }

    /**
     * @dataProvider getParameterNamesDataProvider
     *
     * @param string[] $expectedKeys
     */
    public function testGetParameterNames(DataSetCollection $dataSetCollection, array $expectedKeys): void
    {
        $keys = $dataSetCollection->getParameterNames();

        $this->assertSame($expectedKeys, $keys);
    }

    /**
     * @return array[]
     */
    public function getParameterNamesDataProvider(): array
    {
        return [
            'empty' => [
                'dataSetCollection' => new DataSetCollection([]),
                'expectedKeys' => [],
            ],
            'non-empty' => [
                'dataSetCollection' => new DataSetCollection([
                    'set1' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ]),
                'expectedKeys' => [
                    'key1',
                    'key2',
                ],
            ],
        ];
    }

    /**
     * @dataProvider toArrayDataProvider
     *
     * @param array<string, array<int|string, string>> $expectedData
     */
    public function testToArray(DataSetCollection $dataSetCollection, array $expectedData): void
    {
        $this->assertSame($expectedData, $dataSetCollection->toArray());
    }

    /**
     * @return array[]
     */
    public function toArrayDataProvider(): array
    {
        return [
            'empty' => [
                'dataSetCollection' => new DataSetCollection([]),
                'expectedData' => [],
            ],
            'single data set' => [
                'dataSetCollection' => new DataSetCollection([
                    'set1' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ]),
                'expectedData' => [
                    'set1' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ],
            ],
            'multiple data sets' => [
                'dataSetCollection' => new DataSetCollection([
                    'set1' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                    'set2' => [
                        'key1' => 'value3',
                        'key2' => 'value4',
                    ],
                ]),
                'expectedData' => [
                    'set1' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                    'set2' => [
                        'key1' => 'value3',
                        'key2' => 'value4',
                    ],
                ],
            ],
        ];
    }
}
