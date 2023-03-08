<?php

namespace App\Services\UrlShortener\Contracts;

interface UrlShortenerInterface
{
    public function shortUrl(string $url): string|false;
}
