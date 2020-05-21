<?php

declare(strict_types=1);

namespace webignition\BasilModels\Action\Factory;

class UnknownActionTypeException extends MalformedDataException
{
    public string $type;

    /**
     * @param array<mixed> $data
     * @param string $type
     */
    public function __construct(array $data, string $type)
    {
        parent::__construct($data);

        $this->type = $type;
    }
}
