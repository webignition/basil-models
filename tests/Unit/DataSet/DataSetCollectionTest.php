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
    public function testCreate(array $data, array $expectedDataSets)
    {
        $dataSetCollection = new DataSetCollection($data);

        $this->assertCount(count($expectedDataSets), $dataSetCollection);

        foreach ($dataSetCollection as $index => $dataSet) {
            $expectedDataSet = $expectedDataSets[$index];

            $this->assertEquals($expectedDataSet, $dataSet);
        }
    }

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
     * @param DataSetCollection $dataSetCollection
     * @param string[] $expectedKeys
     */
    public function testGetParameterNames(DataSetCollection $dataSetCollection, array $expectedKeys)
    {
        $keys = $dataSetCollection->getParameterNames();

        $this->assertSame($expectedKeys, $keys);
    }

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
}
