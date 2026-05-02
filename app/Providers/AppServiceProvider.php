<?php

namespace App\Providers;

use App\Domain\EVE\Repositories\CachedCharacterRepository;
use App\Domain\Infrastructure\Concurrency\CacheSyncLock;
use App\Domain\Infrastructure\Esi\DataProviders\EsiCharacterDataProvider;
use App\Domain\Infrastructure\Esi\Gateway\EsiGateway;
use App\Domain\Infrastructure\Esi\jobs\SyncEsiCharacter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('esi.character', function ($app) {
            return new EsiGateway(
                $app->make(CachedCharacterRepository::class),
                $app->make(EsiCharacterDataProvider::class),
                $app->make(CacheSyncLock::class),
                'character',
                SyncEsiCharacter::class
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
