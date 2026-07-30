<?php

namespace App\Http\Controllers\Web\Eve;


use App\Domain\SDE\Services\Actions\ListRegionsAction;
use Illuminate\Http\Request;

class RegionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ListRegionsAction $listRegionsAction)
    {
        $search = '';
        if ($request->filled('search')) {
            $search = $request->search;
        }

        return $listRegionsAction->listRegions($search);
    }
}
