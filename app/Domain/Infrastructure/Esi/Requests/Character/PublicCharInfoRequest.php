<?php

namespace App\Domain\Infrastructure\Esi\Requests\Character;

use App\Domain\Infrastructure\Esi\Requests\EsiRequest;

class PublicCharInfoRequest extends EsiRequest
{
    public function __construct(private int $characterId) {}

    public function endpoint(): string
    {
        return '/characters/%d';
    }
    public function id(): int|string|null
    {
        return $this->characterId;
    }
}
