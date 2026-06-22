<?php

namespace App\Domain\Health\Mapping;

use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Exceptions\SynchronizationFailedException;

final class HealthExceptionMapping
{
    /**
     * @var array<int, class-string<HealthException>>
     */
    private const MAP = [
        SynchronizationFailedException::class,
    ];

    /**
     * Determine whether an exception should be reported.
     */
    public static function has(string $exception): bool
    {
        return in_array($exception, self::MAP, true);
    }

    /**
     * Get all registered exceptions.
     *
     * @return array<int, class-string<HealthException>>
     */
    public static function all(): array
    {
        return self::MAP;
    }
}
