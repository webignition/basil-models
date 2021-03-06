<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

class Configuration implements ConfigurationInterface
{
    public function __construct(
        private string $browser,
        private string $url
    ) {
    }

    public function getBrowser(): string
    {
        return $this->browser;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function validate(): int
    {
        if ('' === trim($this->browser)) {
            return self::VALIDATION_STATE_BROWSER_EMPTY;
        }

        if ('' === trim($this->url)) {
            return self::VALIDATION_STATE_URL_EMPTY;
        }

        return self::VALIDATION_STATE_VALID;
    }
}
