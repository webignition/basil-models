<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action\Factory;

class MalformedDataException extends \Exception
{
    /**
     * @var array<mixed>
     */
    private $data;

    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        parent::__construct('');

        $this->data = $data;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
