<?php

namespace App\Services\UrlShortener\TinyUrl;

use App\Services\UrlShortener\Contracts\UrlShortenerInterface;
use App\Services\UrlShortener\Repositories\HttpClient;
use Exception;
use Illuminate\Http\Client\Response;

class UrlShortener implements UrlShortenerInterface
{
    private const TINYURL_API_URL = 'https://tinyurl.com/api-create.php?url=';

    public function __construct(
        private readonly HttpClient $apiClient,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function shortUrl(string $url): string
    {
        $response = $this->apiClient->makeGetRequest($this->generateApiUrl($url));

        if (!$this->apiClient->checkOkStatusOfRequest($response)){
            throw new Exception($this->apiClient->getReason($response));
        }

        return $this->generateResponseUrl($response);
    }

    private function generateApiUrl(string $url): string
    {
        return self::TINYURL_API_URL . $url;
    }

    private function generateResponseUrl(Response $response): string
    {
        return '<' . $this->apiClient->getBody($response) . '>';
    }
}
