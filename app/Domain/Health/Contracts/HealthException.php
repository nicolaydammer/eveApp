<?php

namespace App\Domain\Health\Contracts;

use App\Domain\Health\Enums\HealthSource;

interface HealthException extends \Throwable
{
    /**
     * Returns the unique business operation that failed.
     *
     * Example:
     * - industry.sync.fetch-indices
     * - market.sync.fetch-prices
     * - sde.download
     */
    public function code(): string;

    /**
     * Returns the owning domain.
     */
    public function source(): HealthSource;

    /**
     * Returns additional debugging context.
     */
    public function context(): array;
}
