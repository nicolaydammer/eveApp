<?php

namespace App\Domain\Synchronization\Mapping;

use App\Domain\Synchronization\Contracts\SynchronizationInterface;
use InvalidArgumentException;

final class SynchronizationClassMapping
{
    private const MAP = [
        // 'industry-cost-indices' => SynchronizeIndustryCostIndices::class,
        // 'market-prices' => SynchronizeMarketPrices::class,
        // 'sde-import' => SynchronizeSde::class,
    ];

    /**
     * Determine whether a synchronization has an implementation.
     */
    public static function has(string $name): bool
    {
        return isset(self::MAP[$name]);
    }

    /**
     * Resolve the synchronization implementation.
     *
     * @throws InvalidArgumentException
     */
    public static function resolve(string $name): SynchronizationInterface
    {
        if (! self::has($name)) {
            throw new InvalidArgumentException(
                "Synchronization '{$name}' is not registered."
            );
        }

        return app(self::MAP[$name]);
    }

    /**
     * Get all registered synchronizations.
     *
     * @return array<string, class-string>
     */
    public static function all(): array
    {
        return self::MAP;
    }

    /**
     * Get all registered synchronization names.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_keys(self::MAP);
    }
}
