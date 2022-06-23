<?php

declare(strict_types=1);

namespace webignition\BasilModels\Provider\Exception;

class UnknownItemException extends \Exception
{
    public const TYPE_DATASET = 'dataset';
    public const TYPE_IDENTIFIER = 'identifier';
    public const TYPE_PAGE = 'page';
    public const TYPE_STEP = 'step';

    private ?string $testName = null;
    private ?string $stepName = null;
    private ?string $content = null;

    public function __construct(
        private readonly string $type,
        private readonly string $name,
    ) {
        parent::__construct(sprintf('Unknown %s "%s"', $type, $name));
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTestName(): ?string
    {
        return $this->testName;
    }

    public function setTestName(string $testName): void
    {
        $this->testName = $testName;
    }

    public function getStepName(): ?string
    {
        return $this->stepName;
    }

    public function setStepName(string $stepName): void
    {
        $this->stepName = $stepName;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
