<?php

namespace App\Domain\Health\Exceptions;

use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Enums\HealthSource;
use RuntimeException;
use Throwable;

class SynchronizationFailedException extends RuntimeException implements HealthException
{
    public function __construct(
        private readonly string $healthCode,
        private readonly array $healthContext = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct('', 0, $previous);
    }

    public function code(): string
    {
        return $this->healthCode;
    }

    public function source(): HealthSource
    {
        return HealthSource::Synchronization;
    }

    public function context(): array
    {
        return $this->healthContext;
    }
}
