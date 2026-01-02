<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Assertion;

use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\Statement;

/**
 * @phpstan-import-type SerializedAssertion from AssertionInterface
 */
class Assertion extends Statement implements AssertionInterface
{
    private const KEY_OPERATOR = 'operator';

    public function __construct(
        string $source,
        string $identifier,
        private readonly string $operator,
        ?string $value = null
    ) {
        parent::__construct($source, $identifier, $value);
    }

    /**
     * @return StatementType::ASSERTION
     */
    public function getStatementType(): StatementType
    {
        return StatementType::ASSERTION;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function equals(AssertionInterface $assertion): bool
    {
        return
            $this->getIdentifier() === $assertion->getIdentifier()
            && $this->operator === $assertion->getOperator()
            && $this->getValue() === $assertion->getValue();
    }

    public function normalise(): AssertionInterface
    {
        $identifier = (string) $this->getIdentifier();
        $source = $identifier . ' ' . $this->operator;
        $value = $this->getValue();
        if (null !== $value) {
            $source .= ' ' . $value;
        }

        return new Assertion($source, $identifier, $this->operator, $value);
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
     * @return SerializedAssertion
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        \assert($data['statement-type'] === $this->getStatementType()->value);

        $data[self::KEY_OPERATOR] = $this->operator;

        return $data;
    }
}
