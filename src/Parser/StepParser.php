<?php

declare(strict_types=1);

namespace webignition\BasilModels\Parser;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\DataSet\DataSetCollection;
use webignition\BasilModels\Model\Step\Step;
use webignition\BasilModels\Model\Step\StepInterface;
use webignition\BasilModels\Parser\Exception\UnparseableActionException;
use webignition\BasilModels\Parser\Exception\UnparseableAssertionException;
use webignition\BasilModels\Parser\Exception\UnparseableStepException;

class StepParser implements DataParserInterface
{
    private const KEY_ACTIONS = 'actions';
    private const KEY_ASSERTIONS = 'assertions';
    private const KEY_IMPORT_NAME = 'use';
    private const KEY_DATA = 'data';
    private const KEY_ELEMENTS = 'elements';

    public function __construct(
        private ActionParser $actionParser,
        private AssertionParser $assertionParser
    ) {
    }

    public static function create(): StepParser
    {
        return new StepParser(
            ActionParser::create(),
            AssertionParser::create()
        );
    }

    /**
     * @param array<mixed> $data
     *
     * @throws UnparseableStepException
     */
    public function parse(array $data): StepInterface
    {
        $actionsData = $data[self::KEY_ACTIONS] ?? [];
        if (!is_array($actionsData)) {
            throw UnparseableStepException::createForInvalidActionsData($data);
        }

        try {
            $actions = $this->parseActions($actionsData);
        } catch (UnparseableActionException $unparseableActionException) {
            throw UnparseableStepException::createForUnparseableAction($data, $unparseableActionException);
        }

        $assertionsData = $data[self::KEY_ASSERTIONS] ?? [];
        if (!is_array($assertionsData)) {
            throw UnparseableStepException::createForInvalidAssertionsData($data);
        }

        try {
            $assertions = $this->parseAssertions($assertionsData);
        } catch (UnparseableAssertionException $unparseableAssertionException) {
            throw UnparseableStepException::createForUnparseableAssertion(
                $data,
                $unparseableAssertionException
            );
        }

        $step = new Step($actions, $assertions);
        $step = $this->setImportName($step, $data[self::KEY_IMPORT_NAME] ?? null);
        $step = $this->setData($step, $data[self::KEY_DATA] ?? null);

        return $this->setIdentifiers($step, $data[self::KEY_ELEMENTS] ?? null);
    }

    /**
     * @param array<mixed> $actionsData
     *
     * @throws UnparseableActionException
     *
     * @return ActionInterface[]
     */
    private function parseActions(array $actionsData): array
    {
        $actions = [];

        foreach ($actionsData as $actionString) {
            if (is_string($actionString)) {
                $actions[] = $this->actionParser->parse($actionString);
            }
        }

        return $actions;
    }

    /**
     * @param array<mixed> $assertionsData
     *
     * @throws UnparseableAssertionException
     *
     * @return AssertionInterface[]
     */
    private function parseAssertions(array $assertionsData): array
    {
        $assertions = [];

        foreach ($assertionsData as $assertionString) {
            if (is_string($assertionString)) {
                $assertions[] = $this->assertionParser->parse($assertionString);
            }
        }

        return $assertions;
    }

    /**
     * @param mixed $importName
     */
    private function setImportName(StepInterface $step, $importName): StepInterface
    {
        if (!is_string($importName)) {
            $importName = null;
        }

        if (is_string($importName)) {
            $step = $step->withImportName($importName);
        }

        return $step;
    }

    /**
     * @param mixed $data
     */
    private function setData(StepInterface $step, $data): StepInterface
    {
        if (is_array($data)) {
            $step = $step->withData(new DataSetCollection($data));
        }

        if (is_string($data)) {
            $step = $step->withDataImportName($data);
        }

        return $step;
    }

    /**
     * @param mixed $identifiers
     */
    private function setIdentifiers(StepInterface $step, $identifiers): StepInterface
    {
        if (is_array($identifiers)) {
            $step = $step->withIdentifiers($identifiers);
        }

        return $step;
    }
}
