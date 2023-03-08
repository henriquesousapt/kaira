<?php

namespace Tests\Feature\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShortUrlControllerTest extends TestCase
{
    private const URL = '/api/v1/short-urls';
    private const HEADERS = [
        'Authorization' => 'Bearer []{}',
    ];

    public function test_fails_url_required(): void
    {
        $response = $this->withHeaders(self::HEADERS)
            ->post(self::URL);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'errors' => [
                'url' => [
                    'The url field is required.',
                ],
            ],
        ]);
    }
    public function test_fails_url_is_invalid(): void
    {
        $response = $this->withHeaders(self::HEADERS)
            ->post(self::URL, ['url' => 'invalidurl']);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'errors' => [
                'url' => [
                    'The url field must be a valid URL.',
                ],
            ],
        ]);
    }

    public function test_request_is_successful(): void
    {
        $response = $this->withHeaders(self::HEADERS)
            ->post(self::URL, ['url' => 'https://example.com/12345']);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'url',
        ]);
    }
}
