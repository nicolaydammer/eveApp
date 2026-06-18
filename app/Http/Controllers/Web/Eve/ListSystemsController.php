<?php

namespace App\Http\Controllers\Web\Eve;

use App\Domain\SDE\Services\Actions\ListSystemsAction;
use Illuminate\Http\Request;

class ListSystemsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ListSystemsAction $listSystemsAction)
    {
        $search = '';
        if ($request->filled('search')) {
            $search = $request->search;
        }

        return $listSystemsAction->listSystems($search);
    }
}
