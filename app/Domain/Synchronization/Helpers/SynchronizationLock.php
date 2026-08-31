<?php

namespace App\Domain\Synchronization\Helpers;

use App\Domain\Synchronization\Models\Synchronization;
use Illuminate\Support\Facades\Cache;

class SynchronizationLock
{
    public static function isLocked(Synchronization $synchronization): bool
    {
        return Cache::has(self::key($synchronization));
    }

    public static function lock(
        Synchronization $synchronization,
        int $minutes,
    ): void {
        Cache::put(
            self::key($synchronization),
            true,
            now()->addMinutes($minutes),
        );
    }

    private static function key(Synchronization $synchronization): string
    {
        return "synchronization-lock:{$synchronization->name}";
    }
}
