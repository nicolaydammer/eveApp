<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\SDE\Models\IndustryActivity;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class IndustryController
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        return Inertia::render('Industry');
    }

    public function activities(): JsonResponse
    {
        $tableColumns = [
            1 => 'manufacturing',
            3 => 'researchTime',
            4 => 'researchMaterial',
            5 => 'copying',
            8 => 'invention',
            9 => 'reaction'
        ];

        $data = IndustryActivity::query()
            ->get(['_key', 'name'])
            ->map(function (IndustryActivity $activity) use ($tableColumns) {
                return [
                    '_key' => $activity->_key,
                    'name' => $activity->name,
                    'source' => $tableColumns[$activity->_key] ?? null,
                ];
            })
            ->values()
            ->toArray();

        return response()->json($data);
    }
}
