<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\IndustryCalculator\ViewModels\BlueprintManufactureViewModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, BlueprintManufactureViewModel $blueprintManufactureViewModel)
    {
        $search = '';
        if ($request->filled('search')) {
            $search = $request->search;
        }

        return Inertia::render('Industry', [
            'search' => $search,
            'results' => $blueprintManufactureViewModel->toArray($search)
        ]);
    }
}
