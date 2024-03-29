<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Provider\DataSet;

use webignition\BasilModels\Model\DataSet\DataSetCollection;
use webignition\BasilModels\Model\DataSet\DataSetCollectionInterface;
use webignition\BasilModels\Provider\DataSet\DataSetProvider;
use webignition\BasilModels\Provider\Exception\UnknownItemException;

class DataSetProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array<string, DataSetCollectionInterface> $dataSetCollections
     */
    public function testCreate(array $dataSetCollections, DataSetProvider $expectedDataSetProvider): void
    {
        $this->assertEquals($expectedDataSetProvider, new DataSetProvider($dataSetCollections));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'dataSetCollections' => [],
                'expectedDataSetProvider' => new DataSetProvider([]),
            ],
            'invalid data set collections' => [
                'dataSetCollections' => [
                    1,
                    true,
                    [],
                ],
                'expectedDataSetProvider' => new DataSetProvider([]),
            ],
            'valid data set collections' => [
                'dataSetCollections' => [
                    'data_set_collection_1' => new DataSetCollection([
                        '0' => [
                            'foo' => 'bar',
                        ],
                    ]),
                    'data_set_collection_2' => new DataSetCollection([
                        'name' => [
                            'fizz' => 'buzz',
                        ],
                    ]),
                ],
                'expectedDataSetProvider' => new DataSetProvider([
                    'data_set_collection_1' => new DataSetCollection([
                        '0' => [
                            'foo' => 'bar',
                        ],
                    ]),
                    'data_set_collection_2' => new DataSetCollection([
                        'name' => [
                            'fizz' => 'buzz',
                        ],
                    ]),
                ]),
            ],
        ];
    }

    public function testFind(): void
    {
        $importName = 'data_provider_import_name';
        $dataSetCollection = new DataSetCollection([]);

        $provider = new DataSetProvider([
            $importName => $dataSetCollection,
        ]);

        $this->assertSame($dataSetCollection, $provider->find($importName));
    }

    public function testFindThrowsUnknownItemException(): void
    {
        $this->expectException(UnknownItemException::class);
        $this->expectExceptionMessage('Unknown dataset "data_provider_import_name"');

        $provider = new DataSetProvider([]);
        $provider->find('data_provider_import_name');
    }
}
