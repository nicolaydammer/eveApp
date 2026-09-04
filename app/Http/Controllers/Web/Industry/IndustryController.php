<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\SDE\Models\IndustryActivity;
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

    public function activities()
    {
        return IndustryActivity::query()
            ->get([
                '_key',
                'name'
            ])
            ->toArray();
    }
}
