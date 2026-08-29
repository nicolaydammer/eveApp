<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\Models\MapSolarSystem;

class ResolveRegionIdAction
{
    public function fromSystemIds(array $systemIds): array
    {
        return MapSolarSystem::query()
            ->whereIn('_key', $systemIds)
            ->pluck('regionID')
            ->unique()
            ->values()
            ->all();
    }
}
