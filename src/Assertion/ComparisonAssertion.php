<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion;

class ComparisonAssertion extends Assertion implements ComparisonAssertionInterface
{
    private const KEY_VALUE = 'value';

    private string $value;

    public function __construct(string $source, string $identifier, string $comparison, string $value)
    {
        parent::__construct($source, $identifier, $comparison);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function withValue(string $value): ComparisonAssertionInterface
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    public function equals(AssertionInterface $assertion): bool
    {
        if (!parent::equals($assertion)) {
            return false;
        }

        if (!$assertion instanceof ComparisonAssertionInterface) {
            return false;
        }

        return $this->value === $assertion->getValue();
    }

    public function normalise(): AssertionInterface
    {
        $normalised = parent::normalise();

        if ($normalised instanceof ComparisonAssertionInterface) {
            $normalised = $normalised->withValue($this->value);
        }

        return $normalised;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_VALUE => $this->value,
        ]);
    }

    public static function fromArray(array $data): AssertionInterface
    {
        $assertion = parent::fromArray($data);

        return new ComparisonAssertion(
            $assertion->getSource(),
            $assertion->getIdentifier(),
            $assertion->getComparison(),
            (string) ($data[self::KEY_VALUE] ?? '')
        );
    }
}
