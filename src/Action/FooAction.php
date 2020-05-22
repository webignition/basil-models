<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

use webignition\BasilModels\FooStatement;

class FooAction extends FooStatement implements FooActionInterface
{
    private const KEY_TYPE = 'type';
    private const KEY_ARGUMENTS = 'arguments';
    private const KEY_IDENTIFIER = 'identifier';
    private const KEY_VALUE = 'value';

    private string $type;
    private ?string $arguments;
    private ?string $identifier;
    private ?string $value;

    public function __construct(
        string $source,
        string $type,
        ?string $arguments = null,
        ?string $identifier = null,
        ?string $value = null
    ) {
        parent::__construct($source);

        $this->source = $source;
        $this->type = $type;
        $this->arguments = $arguments;
        $this->identifier = $identifier;
        $this->value = $value;
    }

    public function getStatementType(): string
    {
        return 'action';
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getArguments(): ?string
    {
        return $this->arguments;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data[self::KEY_TYPE] = $this->type;

        if (null !== $this->arguments) {
            $data[self::KEY_ARGUMENTS] = $this->arguments;
        }

        if (null !== $this->identifier) {
            $data[self::KEY_IDENTIFIER] = $this->identifier;
        }

        if (null !== $this->value) {
            $data[self::KEY_VALUE] = $this->value;
        }

        return $data;
    }
}
