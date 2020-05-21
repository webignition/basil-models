<?php

declare(strict_types=1);

namespace webignition\BasilModels\Test;

class Configuration implements ConfigurationInterface
{
    private string $browser;
    private string $url;

    public function __construct(string $browser, string $url)
    {
        $this->browser = $browser;
        $this->url = $url;
    }

    public function getBrowser(): string
    {
        return $this->browser;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
