<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilModels\Model\Page\Page;
use webignition\BasilModels\Model\Page\PageInterface;
use webignition\BasilModels\Parser\Exception\InvalidPageException;
use webignition\BasilModels\Parser\PageParser;

class PageParserTest extends TestCase
{
    private PageParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = PageParser::create();
    }

    /**
     * @param array<mixed> $pageData
     */
    #[DataProvider('parseThrowsEmptyUrlExceptionDataProvider')]
    public function testParseThrowsEmptyUrlException(array $pageData): void
    {
        self::expectException(InvalidPageException::class);
        self::expectExceptionMessage('url is empty');

        $this->parser->parse('import name', $pageData);
    }

    /**
     * @return array<mixed>
     */
    public static function parseThrowsEmptyUrlExceptionDataProvider(): array
    {
        return [
            'no data' => [
                'pageData' => [
                ],
            ],
            'not a string' => [
                'pageData' => [
                    'url' => true,
                ],
            ],
            'empty' => [
                'pageData' => [
                    'url' => '',
                ],
            ],
            'whitespace-only' => [
                'pageData' => [
                    'url' => '  ',
                ],
            ],
        ];
    }

    /**
     * @param array<mixed> $pageData
     */
    #[DataProvider('parseDataProvider')]
    public function testParse(string $importName, array $pageData, PageInterface $expectedPage): void
    {
        $this->assertEquals($expectedPage, $this->parser->parse($importName, $pageData));
    }

    /**
     * @return array<mixed>
     */
    public static function parseDataProvider(): array
    {
        return [
            'valid url' => [
                'importName' => 'import_name',
                'pageData' => [
                    'url' => 'http://example.com/',
                ],
                'expectedPage' => new Page('import_name', 'http://example.com/'),
            ],
            'invalid elements; not an array' => [
                'importName' => '',
                'pageData' => [
                    'url' => 'http://example.com/',
                    'elements' => 'string',
                ],
                'expectedPage' => new Page('', 'http://example.com/', []),
            ],
            'valid elements' => [
                'importName' => '',
                'pageData' => [
                    'url' => 'http://example.com/',
                    'elements' => [
                        'heading' => '$".heading"',
                    ],
                ],
                'expectedPage' => new Page('', 'http://example.com/', [
                    'heading' => '$".heading"',
                ]),
            ],
            'valid elements with parent references' => [
                'importName' => '',
                'pageData' => [
                    'url' => 'http://example.com/',
                    'elements' => [
                        'form' => '$".form"',
                        'form_input' => '$"{{ form }} .input"',
                    ],
                ],
                'expectedPage' => new Page('', 'http://example.com/', [
                    'form' => '$".form"',
                    'form_input' => '$"{{ form }} .input"',
                ]),
            ],
        ];
    }
}
