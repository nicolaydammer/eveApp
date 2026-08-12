<?php

namespace App\Domain\Infrastructure\Esi\Requests\Industry;

use App\Domain\Infrastructure\Esi\Requests\EsiRequest;

class ListSystemCostIndicesRequest extends EsiRequest
{
    public function endpoint(): string
    {
        return '/industry/systems';
    }
    public function id(): int|string|null
    {
        return null;
    }
}
