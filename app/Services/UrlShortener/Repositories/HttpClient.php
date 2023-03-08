<?php

namespace App\Services\UrlShortener\Repositories;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpClient
{
    public function makeGetRequest(string $url): Response
    {
        return Http::get($url);
    }
    public function checkOkStatusOfRequest(Response $response): bool
    {
        return $response->ok();
    }
    public function getReason(Response $response): string
    {
        return $response->reason();
    }
    public function getBody(Response $response): string
    {
        return $response->body();
    }
}
