<?php

namespace App\Providers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            URL::forceScheme('https');
        }

        Http::globalRequestMiddleware(function (Request $request) {
            return $request->withHeader(
                'X-User-Agent',
                implode(' ', array_filter([
                    'EveApp/0.1.0',
                    '(+https://github.com/nicolaydammer/eveApp)',
                    'discord:nicolay12866',
                    'eve:nicolay12866 dammer',
                    'email:mail@nmdammer.com',
                ]))
            );
        });
    }
}
