<?php

namespace App\Domain\IndustryCalculator\ViewModels;

use App\Domain\Infrastructure\SDE\Models\Blueprint\Blueprint;

class BlueprintManufactureViewModel
{
    public function toArray(?string $search): array
    {
        if (empty($search)) {
            return [];
        }

        try {
            // Normalize variants for a light multi-pronged match constraint at the DB layer
            $lowerSearch = mb_strtolower($search);
            $upperFirstSearch = ucfirst($lowerSearch);

            return Blueprint::query()
                ->whereRelation('type', function ($query) use ($lowerSearch, $upperFirstSearch) {
                    // Pull records matching either casing variant to ensure we catch them
                    $query->where('name->en', 'like', "%{$lowerSearch}%")
                        ->orWhere('name->en', 'like', "%{$upperFirstSearch}%");
                })
                ->with('type:_key,name')
                // Pull slightly more rows than needed so we have a margin for local filtering
                ->limit(30)
                ->get()
                // Perform a true, bulletproof case-insensitive filter in memory
                ->filter(function ($blueprint) use ($lowerSearch) {
                    if (!$blueprint->type || empty($blueprint->type->name)) {
                        return false;
                    }

                    $typeName = $blueprint->type->name;
                    $englishName = is_array($typeName) || is_object($typeName)
                        ? ($typeName['en'] ?? $typeName->en ?? '')
                        : $typeName;

                    return mb_strpos(mb_strtolower($englishName), $lowerSearch) !== false;
                })
                // Snap the final array down to exactly your 15 items
                ->take(15)
                ->map(function ($blueprint) {
                    $typeName = $blueprint->type->name;
                    $englishName = is_array($typeName) || is_object($typeName)
                        ? ($typeName['en'] ?? $typeName->en ?? 'Unknown Blueprint')
                        : $typeName;

                    return [
                        '_key' => $blueprint->_key,
                        'name' => $englishName,
                    ];
                })
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
