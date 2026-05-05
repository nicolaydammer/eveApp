<?php

namespace App\Domain\Infrastructure\Concurrency;

use Illuminate\Support\Facades\Cache;

class CacheSyncLock
{
    public function acquire(string $key, int $ttl, callable $callback): void
    {
        $lockKey = $key . ':lock';
        $flagKey = $key . ':syncing';

        if (Cache::has($flagKey)) {
            return;
        }

        Cache::lock($lockKey, $ttl)->get(function () use ($flagKey, $callback) {

            if (Cache::has($flagKey)) {
                return;
            }

            Cache::put($flagKey, true, 60);

            $callback();
        });
    }
}
