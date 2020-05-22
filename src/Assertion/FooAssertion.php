<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\FooStatement;

class FooAssertion extends FooStatement implements FooAssertionInterface
{
    private const KEY_IDENTIFIER = 'identifier';
    private const KEY_OPERATOR = 'operator';
    private const KEY_VALUE = 'value';

    private string $identifier;
    private string $operator;
    private ?string $value;

    public function __construct(string $source, string $identifier, string $operator, ?string $value = null)
    {
        parent::__construct($source);

        $this->identifier = $identifier;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function getStatementType(): string
    {
        return 'assertion';
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function equals(FooAssertionInterface $assertion): bool
    {
        return
            $this->identifier === $assertion->getIdentifier() &&
            $this->operator === $assertion->getOperator() &&
            $this->value === $assertion->getValue();
    }

    public function normalise(): FooAssertionInterface
    {
        $new = clone $this;
        $new->source = $this->identifier . ' ' . $this->operator;

        if (null !== $this->value) {
            $new->source .= ' ' . $this->value;
        }

        return $new;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data[self::KEY_IDENTIFIER] = $this->identifier;
        $data[self::KEY_OPERATOR] = $this->operator;

        if (null !== $this->value) {
            $data[self::KEY_VALUE] = $this->value;
        }

        return $data;
    }
}
