<?php

namespace App\Domain\Infrastructure\Esi\Requests;

use App\Domain\EVE\Models\Character;

abstract class EsiRequest
{
    abstract public function endpoint(): string;

    abstract public function id(): int|string|null;

    public function character(): ?Character
    {
        return null;
    }

    public function requiredScopes(): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
    }
}
