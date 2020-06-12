<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\DataSet;

use webignition\BasilModels\DataSet\DataSet;
use webignition\BasilModels\DataSet\DataSetInterface;

class DataSetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getParameterNamesDataProvider
     *
     * @param array<int|string, string> $data
     * @param string[] $expectedParameterNames
     */
    public function testGetParameterNames(array $data, array $expectedParameterNames)
    {
        $dataSet = new DataSet('0', $data);

        $this->assertSame($expectedParameterNames, $dataSet->getParameterNames());
    }

    public function getParameterNamesDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedParameterNames' => [],
            ],
            'non-empty' => [
                'data' => [
                    '1' => 'value for one',
                    '2' => 'value for two',
                    'three' => 'value for three',
                ],
                'expectedParameterNames' => [
                    '1',
                    '2',
                    'three',
                ],
            ],
            'names are sorted' => [
                'data' => [
                    'bear' => 'like a large dog',
                    'zebra' => 'stripey horse',
                    'aardvark' => 'first animal in the alphabet',
                ],
                'expectedParameterNames' => [
                    'aardvark',
                    'bear',
                    'zebra',
                ],
            ],
        ];
    }

    /**
     * @dataProvider hasParameterNamesDataProvider
     *
     * @param DataSetInterface $dataSet
     * @param string[] $parameterNames
     * @param bool $expectedHasParameterNames
     */
    public function testHasParameterNames(
        DataSetInterface $dataSet,
        array $parameterNames,
        bool $expectedHasParameterNames
    ) {
        $this->assertSame($expectedHasParameterNames, $dataSet->hasParameterNames($parameterNames));
    }

    public function hasParameterNamesDataProvider(): array
    {
        return [
            'empty data set, empty parameter names' => [
                'dataSet' => new DataSet('0', []),
                'parameterNames' => [],
                'expectedHasParameterNames' => true,
            ],
            'empty data set, non-empty parameter names' => [
                'dataSet' => new DataSet('0', []),
                'parameterNames' => [
                    'foo',
                ],
                'expectedHasParameterNames' => false,
            ],
            'non-empty data set, no matching parameter names' => [
                'dataSet' => new DataSet(
                    '0',
                    [
                        'key1' => 'value1',
                        'key2' => 'value2',
                        'key3' => 'value3',
                    ]
                ),
                'parameterNames' => [
                    'key4',
                    'key5',
                ],
                'expectedHasParameterNames' => false,
            ],
            'has parameter names' => [
                'dataSet' => new DataSet(
                    '0',
                    [
                        'key1' => 'value1',
                        'key2' => 'value2',
                        'key3' => 'value3',
                    ]
                ),
                'parameterNames' => [
                    'key1',
                    'key2',
                ],
                'expectedHasParameterNames' => true,
            ],
        ];
    }

    /**
     * @dataProvider getNameDataProvider
     */
    public function testGetName(string $name)
    {
        $dataSet = new DataSet($name, []);

        $this->assertSame($name, $dataSet->getName());
    }

    public function getNameDataProvider(): array
    {
        return [
            'integer string' => [
                'name' => '0',
            ],
            'alpha string' => [
                'name' => 'data set 1',
            ],
        ];
    }

    public function testGetData()
    {
        $this->assertEquals([], (new DataSet('name', []))->getData());
        $this->assertEquals(
            [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
            (new DataSet(
                'name',
                [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]
            ))->getData()
        );
    }

    /**
     * @dataProvider toArrayDataProvider
     *
     * @param DataSetInterface $dataSet
     * @param array<mixed> $expectedArray
     */
    public function testToArray(DataSetInterface $dataSet, array $expectedArray)
    {
        $this->assertEquals($expectedArray, $dataSet->toArray());
    }

    public function toArrayDataProvider(): array
    {
        return [
            'empty' =>  [
                'dataSet' => new DataSet('empty', []),
                'expectedArray' => [
                    'name' => 'empty',
                    'data' => [],
                ],
            ],
            'non-empty' =>  [
                'dataSet' => new DataSet('non-empty', [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
                'expectedArray' => [
                    'name' => 'non-empty',
                    'data' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param DataSetInterface $expectedDataSet
     */
    public function testFromArray(array $data, DataSetInterface $expectedDataSet)
    {
        $this->assertEquals($expectedDataSet, DataSet::fromArray($data));
    }

    public function fromArrayDataProvider(): array
    {
        return [
            'empty' =>  [
                'data' => [],
                'expectedDataSet' => new DataSet('', []),
            ],
            'empty name' =>  [
                'data' => [
                    'data' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ],
                'expectedDataSet' => new DataSet('', [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
            ],
            'empty data' =>  [
                'data' => [
                    'name' => 'non-empty',
                ],
                'expectedDataSet' => new DataSet('non-empty', []),
            ],
            'name, data present, data set is empty' =>  [
                'data' => [
                    'name' => 'empty',
                    'data' => [],
                ],
                'expectedDataSet' => new DataSet('empty', []),
            ],
            'name, data present, data set is non-empty' =>  [
                'data' => [
                    'name' => 'non-empty',
                    'data' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ],
                'expectedDataSet' => new DataSet('non-empty', [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
            ],
        ];
    }

    public function testToArrayFromArray()
    {
        $dataSet = new DataSet('non-empty', [
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $this->assertEquals(
            $dataSet,
            DataSet::fromArray($dataSet->toArray())
        );
    }
}
