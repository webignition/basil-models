<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

use webignition\BasilModels\Statement;

class Assertion extends Statement implements AssertionInterface
{
    private const KEY_IDENTIFIER = 'identifier';
    private const KEY_OPERATOR = 'operator';
    private const KEY_VALUE = 'value';

    public function __construct(
        string $source,
        private string $identifier,
        private string $operator,
        private ?string $value = null
    ) {
        parent::__construct($source);
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

    public function equals(AssertionInterface $assertion): bool
    {
        return
            $this->identifier === $assertion->getIdentifier()
            && $this->operator === $assertion->getOperator()
            && $this->value === $assertion->getValue();
    }

    public function normalise(): AssertionInterface
    {
        $new = clone $this;
        $new->source = $this->identifier . ' ' . $this->operator;

        if (null !== $this->value) {
            $new->source .= ' ' . $this->value;
        }

        return $new;
    }

    public static function isComparisonOperator(string $operator): bool
    {
        return in_array($operator, ['excludes', 'includes', 'is-not', 'is', 'matches']);
    }

    public function isComparison(): bool
    {
        return self::isComparisonOperator($this->operator);
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
