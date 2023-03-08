<?php

namespace App\Providers;

use App\Services\UrlShortener\Contracts\UrlShortenerInterface;
use App\Services\UrlShortener\TinyUrl\UrlShortener;
use Illuminate\Support\ServiceProvider;

class UrlShortenerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            UrlShortenerInterface::class,
            UrlShortener::class,
        );
    }
}
