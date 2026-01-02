<?php

declare(strict_types=1);

namespace webignition\BasilModels\Model\Action;

use webignition\BasilModels\Enum\StatementType;
use webignition\BasilModels\Model\StatementInterface;

/**
 * @phpstan-type SerializedAction array{
 *    'statement-type': value-of<StatementType::ACTION>,
 *    'source': string,
 *    'identifier'?: string,
 *    'value'?: string,
 *    'type': string,
 *    'arguments'?: string
 *  }
 */
interface ActionInterface extends StatementInterface
{
    public function getType(): string;

    public function getArguments(): ?string;

    public function isBrowserOperation(): bool;

    public function isInteraction(): bool;

    public function isInput(): bool;

    public function isWait(): bool;

    /**
     * @return SerializedAction
     */
    public function jsonSerialize(): array;
}
