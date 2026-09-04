<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\IndustryCalculator\ViewModels\BlueprintManufactureViewModel;
use Illuminate\Http\Request;

class BlueprintController
{
    public function search(Request $request, BlueprintManufactureViewModel $blueprintManufactureViewModel)
    {
        $search = '';
        if ($request->filled('search')) {
            $search = $request->search;
        }

        return response()->json([
            'search' => $search,
            'results' => $blueprintManufactureViewModel->toArray($search)
        ]);
    }
}
