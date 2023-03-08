<?php

namespace Tests\Unit\Services\UrlShortener\TinyUrl;

use App\Services\UrlShortener\Repositories\HttpClient;
use App\Services\UrlShortener\TinyUrl\UrlShortener;
use Exception;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    public function test_that_it_throws_exception(): void
    {
        $this->expectException(Exception::class);

        $this->instance(
            HttpClient::class,
            Mockery::mock(HttpClient::class, function (MockInterface $mock) {
                $mock->shouldReceive('makeGetRequest')
                    ->once()
                    ->with('https://tinyurl.com/api-create.php?url=https://example.com/12345');
                $mock->shouldReceive('checkOkStatusOfRequest')
                    ->once()
                    ->andReturn(false);
                $mock->shouldReceive('getReason')
                    ->once()
                    ->andReturn('Error message...');
            })
        );

        $urlShortener = resolve(UrlShortener::class);
        $urlShortener->shortUrl('https://example.com/12345');

        $this->expectException('Error message...');
    }
    public function test_that_it_returns_shortened_url(): void
    {
        $this->instance(
            HttpClient::class,
            Mockery::mock(HttpClient::class, function (MockInterface $mock) {
                $mock->shouldReceive('makeGetRequest')
                    ->once()
                    ->with('https://tinyurl.com/api-create.php?url=https://example.com/12345');
                $mock->shouldReceive('checkOkStatusOfRequest')
                    ->once()
                    ->andReturn(true);
                $mock->shouldReceive('getBody')
                    ->once()
                    ->andReturn('https://tinyurl.com/2634hs2a');
            })
        );

        $urlShortener = resolve(UrlShortener::class);
        $response = $urlShortener->shortUrl('https://example.com/12345');

        $this->assertEquals('<https://tinyurl.com/2634hs2a>', $response);
    }
}
