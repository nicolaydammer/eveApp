<?php

namespace App\Http\Controllers\Web;

use App\Domain\IndustryCalculator\ViewModels\BlueprintManufactureViewModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndustryController
{
    public function index(Request $request, BlueprintManufactureViewModel $blueprintManufactureViewModel)
    {
        $search = '';
        if ($request->filled('search')) {
            $search = $request->search;
        }

        return Inertia::render('Industry', [
            'results' => $blueprintManufactureViewModel->toArray($search)
        ]);
    }

    public function getFullTree(int $_key)
    {
        $action = new \App\Domain\IndustryCalculator\Actions\BlueprintTree();
        return response()->json($action->getTree($_key));
    }

    public function instaBuy(int $_key)
    {
        $action = new \App\Domain\IndustryCalculator\Actions\BlueprintDirectBuy();
        return response()->json($action->instaBuy($_key));
    }
}
