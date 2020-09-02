<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

class Configuration implements ConfigurationInterface
{
    /**
     * @var string[]
     */
    private array $browsers;
    private string $url;

    /**
     * @param string[] $browsers
     * @param string $url
     */
    public function __construct(array $browsers, string $url)
    {
        $this->browsers = array_filter($browsers, function ($item) {
            return '' !== trim($item);
        });
        $this->url = $url;
    }

    public function getBrowsers(): array
    {
        return $this->browsers;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function validate(): int
    {
        if (0 === count($this->browsers)) {
            return self::VALIDATION_STATE_BROWSER_EMPTY;
        }

        if ('' === trim($this->url)) {
            return self::VALIDATION_STATE_URL_EMPTY;
        }

        return self::VALIDATION_STATE_VALID;
    }
}
