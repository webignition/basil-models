<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser\Test;

use webignition\BasilModels\Model\Step\StepCollection;
use webignition\BasilModels\Model\Test\Test;
use webignition\BasilModels\Model\Test\TestInterface;
use webignition\BasilModels\Parser\DataParserInterface;
use webignition\BasilModels\Parser\Exception\InvalidTestException;
use webignition\BasilModels\Parser\Exception\UnparseableStepException;
use webignition\BasilModels\Parser\Exception\UnparseableTestException;
use webignition\BasilModels\Parser\StepParser;

class TestParser implements DataParserInterface
{
    private const KEY_CONFIGURATION = 'config';
    private const KEY_BROWSER = 'browser';
    private const KEY_URL = 'url';
    private const KEY_IMPORTS = 'imports';

    public function __construct(
        private StepParser $stepParser
    ) {
    }

    public static function create(): TestParser
    {
        return new TestParser(
            StepParser::create()
        );
    }

    /**
     * @param array<mixed> $data
     *
     * @throws UnparseableTestException
     * @throws InvalidTestException
     */
    public function parse(array $data): TestInterface
    {
        $configurationData = $data[self::KEY_CONFIGURATION] ?? [];
        $configurationData = is_array($configurationData) ? $configurationData : [];

        $browser = $configurationData[self::KEY_BROWSER] ?? '';
        $browser = is_string($browser) ? trim($browser) : '';
        if ('' === $browser) {
            throw InvalidTestException::createForEmptyBrowser();
        }

        $url = $configurationData[self::KEY_URL] ?? '';
        $url = is_string($url) ? trim($url) : '';
        if ('' === $url) {
            throw InvalidTestException::createForEmptyUrl();
        }

        $stepName = null;

        try {
            $stepNames = array_diff(array_keys($data), [self::KEY_CONFIGURATION, self::KEY_IMPORTS]);
            $steps = [];

            foreach ($stepNames as $stepName) {
                $stepData = $data[$stepName] ?? [];

                if (is_array($stepData)) {
                    $steps[$stepName] = $this->stepParser->parse($stepData);
                }
            }
        } catch (UnparseableStepException $unparseableStepException) {
            if (is_string($stepName)) {
                $unparseableStepException->setStepName($stepName);
            }

            throw new UnparseableTestException($data, $unparseableStepException);
        }

        return new Test($browser, $url, new StepCollection($steps));
    }
}
