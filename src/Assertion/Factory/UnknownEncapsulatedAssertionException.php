<?php

declare(strict_types=1);

namespace webignition\BasilModels\Assertion\Factory;

class UnknownEncapsulatedAssertionException extends \Exception
{
    /**
     * @var array<mixed>
     */
    public array $data;

    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        parent::__construct('');

        $this->data = $data;
    }
}