<?php

namespace App\Domain\Infrastructure\Esi\Requests\Alliance;

use App\Domain\Infrastructure\Esi\Requests\EsiRequest;

class PublicAllianceInfoRequest extends EsiRequest
{
    public function __construct(private int $allianceId) {}

    public function endpoint(): string
    {
        return '/alliances/%d';
    }
    public function id(): int|string|null
    {
        return $this->allianceId;
    }
}
