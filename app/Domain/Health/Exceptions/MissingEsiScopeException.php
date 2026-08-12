<?php

namespace App\Domain\Health\Exceptions;

use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Enums\HealthSource;
use App\Domain\Infrastructure\Esi\Enums\Scope;
use RuntimeException;
use Throwable;

class MissingEsiScopeException extends RuntimeException implements HealthException
{
    public function __construct(
        private readonly Scope $scope,
        private readonly string $healthCode,
        private readonly array $context = [],
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
        return HealthSource::Esi;
    }

    public function context(): array
    {
        $context = array_merge([
            'scope' => $this->scope->value,
        ], $this->context);

        if ($this->getPrevious()?->getMessage()) {
            $context = array_merge([
                'message' => $this->getPrevious()->getMessage(),
            ], $context);
        }

        return $context;
    }
}
