<?php

namespace App\Domain\Health\Exceptions;

use App\Domain\Auth\Entities\Character;
use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Enums\HealthSource;
use RuntimeException;

class EsiRateLimitException extends RuntimeException implements HealthException
{
    public function __construct(
        private readonly string $endpoint,
        private readonly string $method,
        private readonly ?Character $character = null,
    ) {
        parent::__construct(
            message: 'Local ESI rate limit reached.',
        );
    }

    public function code(): string
    {
        return 'esi.request.rate-limit';
    }

    public function source(): HealthSource
    {
        return HealthSource::Esi;
    }

    public function context(): array
    {
        return array_filter([
            'message' => trim($this->getMessage()),
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'character_id' => $this->character?->id,
            'character_name' => $this->character?->name,
        ], fn($value) => $value !== null);
    }
}
