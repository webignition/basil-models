<?php

declare(strict_types=1);

namespace webignition\BasilModels\Tests\Unit;

use webignition\BasilModels\StatementInterface;

abstract class AbstractStatementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param array<mixed> $expectedSerializedData
     */
    public function testJsonSerialize(StatementInterface $statement, array $expectedSerializedData): void
    {
        self::assertSame(
            $this->sortSerializedStatement($expectedSerializedData),
            $this->sortSerializedStatement($statement->jsonSerialize())
        );
    }

    /**
     * @return array<mixed>
     */
    abstract public function jsonSerializeDataProvider(): array;

    /**
     * @param array<mixed> $serializedStatement
     *
     * @return array<mixed>
     */
    private function sortSerializedStatement(array $serializedStatement): array
    {
        ksort($serializedStatement);

        if (array_key_exists('statement', $serializedStatement)) {
            $serializedStatementStatement = $serializedStatement['statement'];
            self::assertIsArray($serializedStatementStatement);

            ksort($serializedStatementStatement);
            $serializedStatement['statement'] = $serializedStatementStatement;
        }

        return $serializedStatement;
    }
}
