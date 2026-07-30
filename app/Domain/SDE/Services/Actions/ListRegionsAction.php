<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\Models\MapRegion;

class ListRegionsAction
{
    public function listRegions(string $search)
    {
        return MapRegion::query()
            ->select('_key')
            ->selectRaw("name->>'en' AS region")
            ->when(
                $search !== '',
                fn($query) => $query->whereRaw(
                    "name->>'en' ILIKE ?",
                    ["%{$search}%"]
                )
            )
            ->orderByRaw("name->>'en'")
            ->get();
    }
}
