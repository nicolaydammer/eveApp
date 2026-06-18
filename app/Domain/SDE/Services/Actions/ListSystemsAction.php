<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\Infrastructure\SDE\Models\MapSolarSystem;

class ListSystemsAction
{
    public function listSystems(string $search)
    {
        return MapSolarSystem::query()
            ->select('_key')
            ->selectRaw("name->>'en' AS system")
            ->when(
                $search !== '',
                fn($query) => $query->whereRaw(
                    "name->>'en' ILIKE ?",
                    ["%{$search}%"]
                )
            )
            ->orderByRaw("name->>'en'")
            ->limit(20)
            ->get();
    }
}
