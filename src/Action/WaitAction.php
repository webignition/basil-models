<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action;

class WaitAction extends Action implements WaitActionInterface
{
    public const TYPE = 'wait';
    private const KEY_DURATION = 'duration';

    private string $duration;

    public function __construct(string $source, string $duration)
    {
        parent::__construct($source, self::TYPE, $duration);

        $this->duration = $duration;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_DURATION => $this->duration,
        ]);
    }

    public static function fromArray(array $data): WaitAction
    {
        $action = parent::fromArray($data);

        return new WaitAction($action->getSource(), (string) ($data[self::KEY_DURATION] ?? ''));
    }
}
