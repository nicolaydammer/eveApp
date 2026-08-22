<?php

namespace App\Domain\Health\Exceptions;

use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Enums\HealthSource;
use RuntimeException;
use Throwable;

class EsiRequestFailedException extends RuntimeException implements HealthException
{
    public function __construct(
        private readonly string $endpoint,
        private readonly string $method,
        private readonly ?int $status = null,
        private readonly mixed $character = null,
        private readonly array $context = [],
        string $message = '',
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: $message,
            code: $status ?? 0,
            previous: $previous,
        );
    }

    public function code(): string
    {
        return 'esi.request.failed';
    }

    public function source(): HealthSource
    {
        return HealthSource::Esi;
    }

    public function context(): array
    {
        $context = array_merge([
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'status' => $this->status,
            'character_id' => $this->character?->id,
            'character_name' => $this->character?->name,
        ], $this->context);

        if ($this->getMessage()) {
            $context = array_merge([
                'message' => trim($this->getMessage()),
            ], $context);
        }

        return array_filter(
            $context,
            fn($value) => $value !== null,
        );
    }
}
