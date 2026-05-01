<?php

namespace App\Domain\Infrastructure\Concurrency;

use Illuminate\Support\Facades\Cache;

class CacheSyncLock
{
    public function acquire(string $key, int $ttl, callable $callback): void
    {
        Cache::lock($key, $ttl)->get($callback);
    }
}
