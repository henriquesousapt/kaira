<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\AuthenticateApi;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticateApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/route-to-test-middleware', function () {
            return 'just testing the middleware';
        })->middleware([AuthenticateApi::class]);
    }

    public function invalidTokens(): array
    {
        return [
            [
                [],
            ],
            [
                ['Authorization' => '[{]}']
            ],
            [
                ['Authorization' => 'Bearer ())']
            ],
            [
                ['Authorization' => 'Bearer {)']
            ],
            [
                ['Authorization' => 'Bearer [{]}']
            ],
            [
                ['Authorization' => 'Bearer (((((((()']
            ],
        ];
    }

    public function validTokens(): array
    {
        return [
            [
                ['Authorization' => 'Bearer ']
            ],
            [
                ['Authorization' => 'Bearer {}']
            ],
            [
                ['Authorization' => 'Bearer {}[]()']
            ],
            [
                ['Authorization' => 'Bearer {([])}']
            ],
        ];
    }

    /**
     * @dataProvider invalidTokens
     */
    public function test_unauthenticated_request_with_invalid_authentication_header(array $headers): void
    {
        $this->withHeaders($headers)
            ->get('/route-to-test-middleware')
            ->assertUnauthorized();
    }

    /**
     * @dataProvider validTokens
     */
    public function test_unauthenticated_request_with_valid_authentication_header(array $headers): void
    {
        $this->withHeaders($headers)
            ->get('/route-to-test-middleware')
            ->assertOk();
    }
}
