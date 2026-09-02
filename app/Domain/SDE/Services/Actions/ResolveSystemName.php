<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\Models\MapSolarSystem;

class ResolveSystemName
{
    public function resolveFromSystemIds(array $systemIds): array
    {
        return MapSolarSystem::query()
            ->select([
                '_key'
            ])
            ->selectRaw("name->>'en' as system")
            ->whereIn('_key', $systemIds)
            ->get()
            ->keyBy('_key')
            ->toArray();
    }
}
