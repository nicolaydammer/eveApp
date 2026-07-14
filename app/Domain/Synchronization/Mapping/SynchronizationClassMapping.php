<?php

namespace App\Domain\Synchronization\Mapping;

use App\Domain\Synchronization\Synchronizations\AbstractSynchronization;
use App\Domain\Synchronization\Synchronizations\IndustryCostIndices;
use InvalidArgumentException;

final class SynchronizationClassMapping
{
    /**
     * Get all registered synchronizations.
     *
     * @return array<string, class-string<AbstractSynchronization>>
     */
    public static function all(): array
    {
        return [
            IndustryCostIndices::name() => IndustryCostIndices::class,
        ];
    }

    /**
     * Determine whether a synchronization has an implementation.
     */
    public static function has(string $name): bool
    {
        return isset(self::all()[$name]);
    }

    /**
     * Resolve the synchronization implementation.
     *
     * @throws InvalidArgumentException
     */
    public static function resolve(string $name): AbstractSynchronization
    {
        $map = self::all();

        if (! isset($map[$name])) {
            throw new InvalidArgumentException(
                "Synchronization '{$name}' is not registered."
            );
        }

        return app($map[$name]);
    }

    /**
     * Get all registered synchronization names.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_keys(self::all());
    }
}
